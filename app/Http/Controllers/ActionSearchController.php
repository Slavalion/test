<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Actions\UserWriteOffBalance;
use App\Enums\ActionGroupStatus;
use App\Enums\ActionItemStatus;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Models\ActionSearch;
use App\Models\ActionSearchGroup;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ActionSearchController extends Controller
{
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

        $actionsQuery = ActionSearchGroup::with('product')->where('user_id', $request->user()->id);

        if ($section == 'process') {
            $actionsQuery->where('status', ActionGroupStatus::PROCESS);
        }

        if ($section == 'done') {
            $actionsQuery->where('status', ActionGroupStatus::DONE);
        }

        $actionGroups = $actionsQuery->orderByDesc('created_at')->get();

        return Inertia::render('ActionSearch', [
            'section' => $section,
            'actions' => $actionGroups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BotRequestAction $botRequestAction, UserWriteOffBalance $userWriteOffBalance): JsonResponse
    {
        $request->validate([
            'actions' => ['required', 'array'],
            'product_code' => ['required', 'string'],
        ]);

        $actionPrice = 5;
        $totalActions = 0;

        foreach ($request->actions as $action) {
            $totalActions += $action['quantity'];
        }

        $groupPrice = $totalActions * $actionPrice * 100;

        if ($groupPrice > $request->user()->balance) {
            return response()->json([
                'message' => 'Недостаточно средств',
            ], 422);
        }

        $actionGroup = ActionSearchGroup::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_code,
            'total' => $totalActions,
            'progress' => 0,
            'status' => ActionGroupStatus::PROCESS,
        ]);

        foreach ($request->actions as $action) {
            for ($i = 0; $i < $action['quantity']; $i++) {
                $task = new Task();
                $task->type = 'wb-action-search';
                $task->status = 'process';
                $task->data = '';
                $task->save();

                $actionCart = ActionSearch::create([
                    'task_id' => $task->id,
                    'action_search_group_id' => $actionGroup->id,
                    'user_id' => $request->user()->id,
                    'product_id' => $actionGroup->product_id,
                    'keywords' => $action['keywords'],
                    'view_time' => $action['view_time'],
                    'status' => ActionItemStatus::PROCESS,
                    'start_at' => now()->addMinutes(rand(0, $action['period']['key'] * 60))->timezone('Europe/Moscow'),
                ]);

                $requestData[] = [
                    'id' => $actionCart->id,
                    'task_id' => $task->id,
                    'type' => $task->type,
                    'user_id' => $actionCart->user_id,
                    'product_id' => $actionCart->product_id,
                    'keywords' => $actionCart->keywords,
                    'view_time' => $actionCart->view_time,
                    'start_at' => $actionCart->start_at->toDateTimeString(),
                ];
            }
        }

        if ($request->user()->role != User::ROLE_ADMIN) {
            Transaction::create([
                'user_id' => $request->user()->id,
                'target_id' => $actionGroup->id,
                'amount' => $groupPrice,
                'type' => TransactionType::WRITE_OFF,
                'target' => TransactionTarget::ACTION_SEARCH_GROUP,
            ]);

            $userWriteOffBalance->handle($request->user(), $groupPrice);
        }

        $botRequestAction->execute($requestData);

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
