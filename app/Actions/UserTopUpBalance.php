<?php

namespace App\Actions;

use App\Events\BalanceUpdate;
use App\Models\User;

class UserTopUpBalance
{
    public function handle(User $user, $balance)
    {
        $user->balance += $balance;
        $user->save();

        BalanceUpdate::dispatch($user);
    }
}
