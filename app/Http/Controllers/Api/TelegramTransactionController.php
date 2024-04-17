<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\TelegramTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramTransactionController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $purchase = Purchase::where('task_id', $request->task_id)->first();

        if (! $purchase) {
            return response()->json(['bad', 'Выкуп с таким task_id не найден'], 400);
        }

        $data = [
            'actual_data' => [
                'user_preferences' => $purchase->user->preferences['paymentChatId'],
            ],
            'bot_data' => [
                ...$request->all(),
            ],
        ];

        TelegramTransaction::create([
            'user_id' => $purchase->user->id,
            'purchase_id' => $purchase->id,
            'data' => $data,
        ]);

        return response()->json(['ok']);
    }
}
