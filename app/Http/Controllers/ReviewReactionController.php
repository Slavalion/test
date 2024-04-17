<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Actions\UserWriteOffBalance;
use App\Enums\ReviewReactionPeriod;
use App\Enums\ReviewReactionStatus;
use App\Enums\ReviewReactionType;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Models\ReviewReaction;
use App\Models\ReviewReactionGroup;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WildberriesService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ReviewReactionController extends Controller
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

        $reviewReactionsQuery = ReviewReactionGroup::with('product')->where('user_id', $request->user()->id);

        if ($section == 'process') {
            $reviewReactionsQuery->where('status', ReviewReactionStatus::PROCESS);
        }

        if ($section == 'done') {
            $reviewReactionsQuery->where('status', ReviewReactionStatus::DONE);
        }

        // if ($request->user()->role == User::ROLE_ADMIN) {
        if (true) {
            $reviewReactionsQuery->with(['reviewReactions' => function (Builder $query) {
                $query->where('status', ReviewReactionStatus::PROCESS);
            }]);
        }

        $reviewReactions = $reviewReactionsQuery->orderByDesc('created_at')->get();

        return Inertia::render('ReviewReactions', [
            'section' => $section,
            'reviewReactions' => $reviewReactions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BotRequestAction $botRequestAction, UserWriteOffBalance $userWriteOffBalance): JsonResponse
    {
        $request->validate([
            'product_code' => ['required'],
            'reactions' => ['required', 'array'],
        ]);

        $totalPrice = 0;

        foreach ($request->reactions as $reqctionsData) {
            $totalPrice += $reqctionsData['total'];
        }

        $totalPrice = $totalPrice * 8 * 100;

        if ($totalPrice > $request->user()->balance) {
            return response()->json([
                'message' => 'Недостаточно средств',
            ], 422);
        }

        foreach ($request->reactions as $reactionData) {
            $reviewReactionGroup = ReviewReactionGroup::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_code,
                'review_id' => $reactionData['id'],
                'type' => ReviewReactionType::fromString($reactionData['type']),
                'period' => ReviewReactionPeriod::from($reactionData['period']),
                'total' => $reactionData['total'],
                'progress' => 0,
                'status' => ReviewReactionStatus::PROCESS,
            ]);

            for ($i = 0; $i < $reviewReactionGroup->total; $i++) {
                $task = new Task();
                $task->type = 'wb-review-reaction';
                $task->status = 'process';
                $task->data = '';
                $task->save();

                $reviewReaction = ReviewReaction::create([
                    'user_id' => $request->user()->id,
                    'task_id' => $task->id,
                    'review_reaction_group_id' => $reviewReactionGroup->id,
                    'product_id' => $request->product_code,
                    'review_id' => $reactionData['id'],
                    'type' => ReviewReactionType::fromString($reactionData['type']),
                    'period' => ReviewReactionPeriod::from($reactionData['period']),
                    'total' => 1,
                    'progress' => 0,
                    'status' => ReviewReactionStatus::PROCESS,
                ]);

                $requestData[] = [
                    'id' => $reviewReaction->id,
                    'task_id' => $task->id,
                    'type' => 'wb-review-reaction',
                    'user_id' => $reviewReaction->user_id,
                    'review_id' => $reviewReaction->review_id,
                    'product_id' => $reviewReaction->product_id,
                    'period' => $reviewReaction->period,
                    'reaction_type' => $reviewReaction->type->toString(),
                    'reaction_total' => 1,
                ];
            }

            if ($request->user()->role != User::ROLE_ADMIN) {
                $reactionPrice = $reviewReactionGroup->total * 8 * 100;

                Transaction::create([
                    'user_id' => $request->user()->id,
                    'target_id' => $reviewReactionGroup->id,
                    'amount' => $reactionPrice,
                    'type' => TransactionType::WRITE_OFF,
                    'target' => TransactionTarget::REVIEW_REACTION_GROUP,
                ]);

                $userWriteOffBalance->handle($request->user(), $reactionPrice);
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

        $wildberriesService = new WildberriesService($request->product_code);
        $wbReviews = $wildberriesService->getReviews();

        return response()->json([
            'product' => $wildberriesService->product,
            'reviews' => $wbReviews,
        ], 200);
    }
}
