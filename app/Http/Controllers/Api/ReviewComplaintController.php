<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReviewComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\ReviewComplaint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewComplaintController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $reviewComplaint = ReviewComplaint::where('task_id', $request->task_id)->first();

        if (! $reviewComplaint) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Review complaint not found!'],
            ], 400);
        }

        $request->validate([
            'status' => ['required', Rule::in(['done'])],
        ]);

        if ($request->status == 'done') {
            if ($reviewComplaint->status != ReviewComplaintStatus::DONE) {
                if ($reviewComplaint->reviewComplaintGroup) {
                    $reviewComplaint->reviewComplaintGroup->increment('progress');

                    if ($reviewComplaint->reviewComplaintGroup->progress >= $reviewComplaint->reviewComplaintGroup->total) {
                        $reviewComplaint->reviewComplaintGroup->status = ReviewComplaintStatus::DONE;
                    }

                    $reviewComplaint->reviewComplaintGroup->save();
                }
            }

            $reviewComplaint->status = ReviewComplaintStatus::DONE;

            $reviewComplaint->save();
        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
