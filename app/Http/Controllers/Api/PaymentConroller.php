<?php

namespace App\Http\Controllers\Api;

use App\Actions\UserTopUpBalance;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentConroller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function tinkoff(Request $request, UserTopUpBalance $userTopUpBalance)
    {
        if ($request->Status != 'CONFIRMED') {
            return response(['status' => 'ok'], 200);
        }

        $order = Order::find($request->OrderId);

        if ($order->status == Order::STATUS_SUCCESS) {
            return response(['status' => 'ok'], 200);
        }

        $order->status = Order::STATUS_SUCCESS;
        $order->save();

        $user = User::find($order->user_id);

        Transaction::create([
            'user_id' => $user?->id,
            'amount' => $order->total,
            'type' => TransactionType::TOP_UP,
            'target' => TransactionTarget::TINKOFF,
        ]);

        $userTopUpBalance->handle($user, $order->total);

        return response(['status' => 'ok'], 200);
    }
}
