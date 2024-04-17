<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $task = Task::find($request->task_id);

        if (! $task) {
            return response()->json(['bad', 'task not found'], 400);
        }

        $purchase = Purchase::where('task_id', $task->id)->first();

        if (! $purchase) {
            return response()->json(['bad', 'task not found (p)'], 400);
        }

        $wallet = Wallet::where('id', $request->wallet_id)->first();

        if (! $wallet) {
            return response()->json(['bad', 'wallet not found'], 400);
        }

        $balance = str_replace([' ', '₽'], '', $request->balance);
        $balance = str_replace(',', '.', $balance);

        $amount = str_replace([' ', '₽'], '', $request->amount);
        $amount = str_replace(',', '.', $amount);

        WalletTransaction::create([
            'user_id' => $purchase->user->id,
            'purchase_id' => $purchase->id,
            'wallet_id' => $wallet->id,
            'wallet_name' => $wallet->name,
            'amount' => $amount,
            'actual_balance' => $balance,
        ]);

        return response()->json(['ok']);
    }
}
