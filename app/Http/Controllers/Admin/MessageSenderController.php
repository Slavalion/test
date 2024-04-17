<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class MessageSenderController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Admin/MessageSenderPage', []);
    }

    public function send(Request $request): JsonResponse
    {
        $roles = [
            1,
            $request->userType['key'],
        ];

        $users = User::select('id', 'telegram_id')->whereIn('role', $roles)->where('telegram_id', '<>', 0)->get();

        $message = str_replace(
            ['_'],
            ['\_'],
            $request->message
        );

        foreach ($users as $user) {
            SendTelegramJob::dispatch($user->telegram_id, $message)->onQueue('telegram-message');
        }

        return response()->json(['status' => 'it`s ok']);
    }
}
