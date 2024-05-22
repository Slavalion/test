<?php

namespace App\Actions;

use App\Enums\BotRequestType;

class RecheckPurchaseAction
{
    /**
     * Execute the action.
     */
    public function handle($taskId)
    {
        $botRequestAction = new BotRequestAction();
        $botRequestAction->execute([
            'type' => BotRequestType::PURCHASE_RECHECK_COURIER_DELIVERY->value,
            'task_id' => $taskId,
        ]);
    }
}
