<?php

namespace App\Classes;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class UserSwitch
{
    public const SESSION_NAME = 'original_user';

    /**
     * Changes the current authorization to the required.
     */
    public static function loginAs(User $user)
    {
        if (! session()->has(self::SESSION_NAME)) {
            session()->put(self::SESSION_NAME, self::getAuth()->id());
        }

        self::getAuth()->loginUsingId($user->getKey(), true);
    }

    /**
     * Returns the previous session, before the user changes.
     */
    public static function logout()
    {
        $id = session()->pull(self::SESSION_NAME);

        self::getAuth()->loginUsingId($id);
    }

    public static function isSwitch(): bool
    {
        return session()->has(self::SESSION_NAME);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected static function getAuth()
    {
        return Auth::guard(config('auth.defaults.guard', 'web'));
    }
}
