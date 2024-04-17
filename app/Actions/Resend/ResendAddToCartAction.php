<?php

namespace App\Actions\Resend;

use App\Actions\BotRequestAction;
use App\Models\ActionCart;

class ResendAddToCartAction
{
    /**
     * Execute the action.
     */
    public function handle(array $taskIDs)
    {

        $productsData = [];

        foreach ($taskIDs as $taskID) {
            $actionCart = ActionCart::where('task_id', $taskID)->first();

            $productsData[] = [
                'id' => $actionCart->id,
                'task_id' => $actionCart->task_id,
                'type' => 'wb-action-add-to-cart',
                'user_id' => $actionCart->user_id,
                'product_id' => $actionCart->product_id,
                'keywords' => $actionCart->keywords,
                'view_time' => $actionCart->view_time,
                'start_at' => $actionCart->start_at->toDateTimeString(),
            ];
        }

        (new BotRequestAction)->execute($productsData);
    }
}
