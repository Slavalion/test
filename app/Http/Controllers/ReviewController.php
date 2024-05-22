<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Actions\UserWriteOffBalance;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Enums\UserRole;
use App\Exports\ReviewsExport;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\Transaction;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => Rule::in([
                'processing',
                'done',
            ]),
        ]);

        $reviewsQuery = Review::with('purchase')->where('user_id', $request->user()->id);

        if ($request->section == 'processing') {
            $reviewsQuery->whereIn('status', ['processing', 'process']);
        }

        if ($request->section == 'done') {
            $reviewsQuery->whereIn('status', ['failed', 'done']);
        }

        if ($request->section == '') {
            $reviews = [];
            $reviewsPaginator = [];
        } else {
            $reviews = $reviewsQuery->orderByDesc('created_at')->paginate(50)->withQueryString();
            $reviewsPaginator = $reviews->toArray()['links'];
            $reviews = $reviews->items();
        }

        return Inertia::render('Reviews', [
            'section' => $request->section ?: null,
            'reviews' => $reviews,
            'availablePurchases' => Purchase::select(DB::raw('count(*) as total'), 'product_id')
                ->with('product')
                ->where('user_id', $request->user()->id)
                ->where('review_id', 0)
                ->where('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP)
                ->groupBy('product_id')
                ->get()
                ->toArray(),
            'reviewsPaginator' => $reviewsPaginator,
        ]);
    }

    public function create(Request $request, BotRequestAction $botRequestAction, UserWriteOffBalance $userWriteOffBalance): JsonResponse
    {
        $purchase = Purchase::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->where('review_id', 0)
            ->where('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP)
            ->orderByDesc('created_at')
            ->first();

        if (! $purchase) {
            return response()->json([
                'errors' => 'Not found purchase',
            ], 422);
        }

        $userService = new UserService($request->user());
        $reviewPrice = $userService->getPrice(UserPriceType::REVIEW);

        if ($reviewPrice > $request->user()->balance) {
            return response()->json([
                'message' => 'Недостаточно средств',
            ], 422);
        }

        $review = new Review();
        $review->user_id = $request->user()->id;
        $review->task_id = $purchase->task_id;
        $review->gender = $request->gender;
        $review->rate = $request->rating;
        $review->review = $request->review ?: '';
        $review->public_at = (new Carbon($request->publish_at))->timezone('Europe/Moscow');
        $review->status = 'processing';

        $review->save();

        $purchase->review_id = $review->id;
        $purchase->save();

        $files = [];

        if ($request->photos) {
            foreach ($request->photos as $file) {
                $files[] = base64_encode(file_get_contents($file->getRealPath()));
            }
        }

        $data = [
            'task_id' => $purchase->task_id,
            'review_id' => $review->id,
            'purchase_id' => $purchase->id,
            'type' => 'wb-review-pub',
            'user_id' => $request->user()->id,
            'gender' => $review->gender,
            'review' => $review->review,
            'rate' => $review->rate,
            'public_at' => $review->public_ts,
            'files' => $files,
        ];

        if ($request->user()->role != UserRole::ADMIN) {
            Transaction::create([
                'user_id' => $request->user()->id,
                'target_id' => $review->id,
                'amount' => $reviewPrice,
                'type' => TransactionType::WRITE_OFF,
                'target' => TransactionTarget::REVIEW,
            ]);

            $userWriteOffBalance->handle($request->user(), $reviewPrice);
        }

        $botRequestAction->execute($data);

        return response()->json([
            'status' => 'ok',
        ], 200);
    }

    public function download(Request $request): BinaryFileResponse
    {
        // $request->validate([
        //     'status' => Rule::in([...self::deliveryStatuses, 'all']),
        // ]);

        $status = $request->status ?: 'all';

        return (new ReviewsExport($request->user()->id, $status))->download('reviews.xlsx');
    }
}
