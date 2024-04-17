<?php

namespace App\Actions\Resend;

use App\Actions\BotRequestAction;
use App\Models\Purchase;

class ResendPurchaseAction
{
    /**
     * Execute the action.
     */
    public function handle(array $taskIDs)
    {
        $productsData = [];

        foreach ($taskIDs as $taskID) {
            $purchase = Purchase::where('task_id', $taskID)->first();

            $paymentData['chat_id'] = $purchase->user->preferences['paymentChatId'];

            $productsData[] = [
                'task_id' => $purchase->task_id,
                'purchase_id' => $purchase->id,
                'type' => 'wb-purchase',
                'user_id' => $purchase->user->id,
                'product_id' => $purchase->product_id,
                'quantity' => $purchase->quantity,
                'gender' => $purchase->gender,
                'size' => $purchase->size,
                'keywords' => $purchase->keywords,
                'address' => $purchase->address,
                'purchase_at' => $purchase->purchase_ts,
                'purchase_type' => $purchase->type,
                'payment_type' => 'telegram',
                'payment_data' => $paymentData,
            ];
        }

        (new BotRequestAction)->execute($productsData);
    }
}
