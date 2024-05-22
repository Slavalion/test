<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TelegramController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $telegramUser = Socialite::driver('telegram')->user();

        $user = User::where('telegram_id', $telegramUser->id)->first();

        if (! $user) {
            // TODO Remove this
            $user = User::where('email', '@'.$telegramUser->nickname)->first();

            if (! $user) {
                $user = new User();

                if (config('mpbtop.demo_mode')) {
                    $user->balance = 3300000;
                    $user->save();
                } else {
                    $user->balance = 1000 * 100;
                }

                if ($request->refCode) {
                    $inviter = User::where('ref_code', $request->refCode)->first();

                    if ($inviter) {
                        $user->inviter_id = $inviter->id;
                    }
                }
            }
        }

        $user->telegram_id = $telegramUser->id;
        $user->name = $telegramUser->name;
        $user->email = $user->email ?: '@'.$telegramUser->nickname;
        $user->password = $user->password ?: Str::password(16);

        $user->save();

        Auth::login($user, true);

        return redirect('/purchase/list');
    }

    public function connect(): RedirectResponse
    {
        $telegramUser = Socialite::driver('telegram')->user();

        Auth::user()->telegram_id = $telegramUser->id;
        Auth::user()->save();

        return redirect()->route('profile.edit');
    }
}
