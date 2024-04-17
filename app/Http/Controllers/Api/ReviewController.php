<?php

namespace App\Http\Controllers\Api;

use App\Actions\ReviewRemoveAction;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public const STATUSES = [
        'failed',
        'done',
    ];

    public function update(Request $request)
    {
        $purchase = Purchase::where('task_id', $request->task_id)->first();

        if (! $purchase) {
            return response()->json(['bad'], 400);
        }

        $review = $purchase->review;

        if (! $review) {
            return response()->json(['bad', 'error' => 'У этого выкупа нет назначенного отзыва'], 400);
        }

        if (in_array($request->status, self::STATUSES)) {

            $review->status = $request->status;
            $review->save();
        }

        return response()->json(['ok']);
    }

    public function cancel(Request $request, ReviewRemoveAction $reviewRemoveAction)
    {
        $purchase = Purchase::where('task_id', $request->task_id)->first();
        $reviewID = $request->review_id;

        if (! $purchase) {
            return response()->json([
                'bad',
                'error' => 'Выкуп с таким task_id не найден',
            ], 400);
        }

        if (! $purchase->review) {
            return response()->json([
                'bad',
                'error' => 'У выкуп с таким task_id нет отзыва',
            ], 400);
        }

        if ($purchase->review->id != $reviewID) {
            return response()->json([
                'bad',
                'error' => 'У выкупа нет отзыва с таким ID',
            ], 400);
        }

        $reviewRemoveAction->handle($purchase->review);

        return response()->json(['ok'], 200);
    }
}
