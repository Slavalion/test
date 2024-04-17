<?php

namespace App\Actions;

use App\Jobs\SendTelegramJob;
use App\Models\User;

class SendUserNotificationAction
{
    /**
     * Execute the action.
     */
    public function handle(User $user, string $message): bool
    {
        if (empty($user->telegram_id)) {
            return false;
        }

        SendTelegramJob::dispatch($user->telegram_id, $message)
            ->onQueue('telegram-message');

        return true;
    }
}
