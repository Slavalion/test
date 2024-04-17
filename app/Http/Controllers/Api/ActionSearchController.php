<?php

namespace App\Http\Controllers\Api;

use App\Enums\ActionGroupStatus;
use App\Enums\ActionItemStatus;
use App\Http\Controllers\Controller;
use App\Models\ActionSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActionSearchController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $action = ActionSearch::where('task_id', $request->task_id)->first();

        if (! $action) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Action search not found!'],
            ], 400);
        }

        $request->validate([
            'status' => ['required', Rule::in(['done'])],
        ]);

        if ($request->status == 'done') {
            if ($action->status != ActionItemStatus::DONE) {
                $action->actionSearchGroup->increment('progress');

                if ($action->actionSearchGroup->progress >= $action->actionSearchGroup->total) {
                    $action->actionSearchGroup->status = ActionGroupStatus::DONE;
                }

                $action->actionSearchGroup->save();
            }

            $action->status = ActionItemStatus::DONE;

            $action->save();
        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
