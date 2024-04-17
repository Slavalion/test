<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActionGroupStatus;
use App\Enums\ActionItemStatus;
use App\Http\Controllers\Controller;
use App\Models\ActionCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActionCartController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $action = ActionCart::where('task_id', $request->task_id)->first();

        if (! $action) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Action cart not found!'],
            ], 400);
        }

        $request->validate([
            'status' => ['required', Rule::in(['done'])],
        ]);

        if ($request->status == 'done') {
            if ($action->status != ActionItemStatus::DONE && $action->actionCartGroup) {
                $action->actionCartGroup->increment('progress');

                if ($action->actionCartGroup->progress >= $action->actionCartGroup->total) {
                    $action->actionCartGroup->status = ActionGroupStatus::DONE;
                }

                $action->actionCartGroup->save();
            }

            $action->status = ActionItemStatus::DONE;

            $action->save();
        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
