<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Actions\UserWriteOffBalance;
use App\Enums\ReviewComplaintStatus;
use App\Enums\ReviewComplaintType;
use App\Enums\ReviewReactionPeriod;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ReviewComplaint;
use App\Models\ReviewComplaintGroup;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WildberriesService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ReviewComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): InertiaResponse
    {
        $request->validate([
            'status' => Rule::in([
                'process',
                'done',
                'all',
            ]),
        ]);

        $section = $request->section ?: 'process';

        $reviewComplaintsQuery = ReviewComplaintGroup::with('product')->where('user_id', $request->user()->id);

        if ($section == 'process') {
            $reviewComplaintsQuery->where('status', ReviewComplaintStatus::PROCESS);
        }

        if ($section == 'done') {
            $reviewComplaintsQuery->where('status', ReviewComplaintStatus::DONE);
        }

        // if ($request->user()->role == User::ROLE_ADMIN) {
        if (true) {
            $reviewComplaintsQuery->with(['reviewComplaints' => function (Builder $query) {
                $query->where('status', ReviewComplaintStatus::PROCESS);
            }]);
        }

        $reviewComplaints = $reviewComplaintsQuery->orderByDesc('created_at')->get();

        return Inertia::render('ReviewComplaints', [
            'section' => $section,
            'reviewComplaints' => $reviewComplaints,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BotRequestAction $botRequestAction, UserWriteOffBalance $userWriteOffBalance): JsonResponse
    {
        $request->validate([
            'product_code' => ['required'],
            'complaints' => ['required', 'array'],
        ]);

        $totalPrice = 0;

        foreach ($request->complaints as $complaintData) {
            $totalPrice += $complaintData['total'];
        }

        $totalPrice = $totalPrice * 30 * 100;

        if ($totalPrice > $request->user()->balance) {
            return response()->json([
                'message' => 'Недостаточно средств',
            ], 422);
        }

        foreach ($request->complaints as $complaintData) {
            $reviewComplaintGroup = ReviewComplaintGroup::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_code,
                'review_id' => $complaintData['id'],
                'type' => ReviewComplaintType::fromString($complaintData['type']),
                'period' => ReviewReactionPeriod::from($complaintData['period']),
                'total' => $complaintData['total'],
                'progress' => 0,
                'status' => ReviewComplaintStatus::PROCESS,
            ]);

            for ($i = 0; $i < $reviewComplaintGroup->total; $i++) {
                $task = new Task();
                $task->type = 'wb-review-reaction';
                $task->status = 'process';
                $task->data = '';
                $task->save();

                $reviewComplaint = ReviewComplaint::create([
                    'user_id' => $request->user()->id,
                    'task_id' => $task->id,
                    'review_complaint_group_id' => $reviewComplaintGroup->id,
                    'product_id' => $request->product_code,
                    'review_id' => $complaintData['id'],
                    'type' => ReviewComplaintType::fromString($complaintData['type']),
                    'period' => ReviewReactionPeriod::from($complaintData['period']),
                    'total' => 1,
                    'progress' => 0,
                    'status' => ReviewComplaintStatus::PROCESS,
                ]);

                $requestData[] = [
                    'id' => $reviewComplaint->id,
                    'task_id' => $task->id,
                    'type' => 'wb-review-complaint',
                    'user_id' => $reviewComplaint->user_id,
                    'review_id' => $reviewComplaint->review_id,
                    'product_id' => $reviewComplaint->product_id,
                    'period' => $reviewComplaint->period,
                    'complaint_type' => $reviewComplaint->type->toString(),
                    'sort_type' => $request->sortType,
                    'sort_order' => $request->sortOrder,
                ];
            }

            if ($request->user()->role != UserRole::ADMIN) {
                $complaintPrice = $reviewComplaintGroup->total * 30 * 100;

                Transaction::create([
                    'user_id' => $request->user()->id,
                    'target_id' => $reviewComplaintGroup->id,
                    'amount' => $complaintPrice,
                    'type' => TransactionType::WRITE_OFF,
                    'target' => TransactionTarget::REVIEW_COMPLAINT_GROUP,
                ]);

                $userWriteOffBalance->handle($request->user(), $complaintPrice);
            }
        }

        $botRequestAction->execute($requestData);

        return response()->json([
            'status' => 'ok',
        ], 200);
    }

    public function search(Request $request)
    {
        $request->validate([
            'product_code' => ['required'],
        ]);

        $product = Product::where('remote_id', $request->product_code)->first();

        $response = Http::get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$request->product_code);
        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return response()->json([
                'message' => 'Товар не найден',
            ], 404);
        }

        $productData = $responseData['data']['products'][0];

        if (! $product) {
            $product = new Product();
        }

        $product->remote_id = $productData['id'];
        $product->price = $productData['salePriceU'];
        $product->name = $productData['name'];
        $product->brand = $productData['brand'];
        $product->image = '';

        $product->save();

        $wildberriesService = new WildberriesService($request->product_code);
        $wbReviews = $wildberriesService->getReviews();

        return response()->json([
            'product' => $wildberriesService->product,
            'reviews' => $wbReviews,
        ], 200);
    }
}
