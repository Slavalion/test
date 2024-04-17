<?php

namespace App\Actions\Resend;

use App\Actions\BotRequestAction;
use App\Models\ActionSearch;

class ResendSearchAction
{
    /**
     * Execute the action.
     */
    public function handle(array $taskIDs)
    {
        $productsData = [];

        foreach ($taskIDs as $taskID) {
            $actionSearch = ActionSearch::where('task_id', $taskID)->first();

            $productsData[] = [
                'id' => $actionSearch->id,
                'task_id' => $actionSearch->task_id,
                'type' => 'wb-action-search',
                'user_id' => $actionSearch->user_id,
                'product_id' => $actionSearch->product_id,
                'keywords' => $actionSearch->keywords,
                'view_time' => $actionSearch->view_time,
                'start_at' => $actionSearch->start_at->toDateTimeString(),
            ];
        }

        (new BotRequestAction)->execute($productsData);

    }
}
