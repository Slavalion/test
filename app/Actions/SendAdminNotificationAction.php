<?php

namespace App\Actions;

use App\Enums\UserRole;
use App\Jobs\SendTelegramJob;
use App\Models\User;

class SendAdminNotificationAction
{
    /**
     * Send notifications to admin users
     */
    public function handle(string $message)
    {
        $users = User::select('id', 'telegram_id')->where('role', UserRole::ADMIN)->where('telegram_id', '<>', 0)->get();

        $message = str_replace(
            ['_'],
            ['\_'],
            $message
        );

        foreach ($users as $user) {
            SendTelegramJob::dispatch($user->telegram_id, $message)->onQueue('telegram-message');
        }
    }
}
