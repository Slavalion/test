<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReviewReactionStatus;
use App\Http\Controllers\Controller;
use App\Models\ReviewReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewReactionController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $reviewReaction = ReviewReaction::where('task_id', $request->task_id)->first();

        if (! $reviewReaction) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Review reaction not found!'],
            ], 400);
        }

        $request->validate([
            'status' => ['required', Rule::in(['done'])],
        ]);

        if ($request->status == 'done') {
            if ($reviewReaction->status != ReviewReactionStatus::DONE) {
                $reviewReaction->reviewReactionGroup->increment('progress');

                if ($reviewReaction->reviewReactionGroup->progress >= $reviewReaction->reviewReactionGroup->total) {
                    $reviewReaction->reviewReactionGroup->status = ReviewReactionStatus::DONE;
                }

                $reviewReaction->reviewReactionGroup->save();
            }

            $reviewReaction->progress = 1;
            $reviewReaction->status = ReviewReactionStatus::DONE;

            $reviewReaction->save();
        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
