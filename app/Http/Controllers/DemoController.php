<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DemoUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    public function auth(Request $request)
    {
        if (! config('mpbtop.demo_mode')) {
            abort(404);
        }

        if (Auth::check()) {
            return redirect(route('purchase.list'));
        }

        if (! $request->ip()) {
            return view('demo.error', ['message' => 'Не удалось авторизоваться в демо режиме. Обратитесь в поддержку!']);
        }

        $totalUsers = User::where('ip', $request->ip())->count();

        if ($totalUsers >= 5) {
            return view('demo.error', ['message' => 'С вашего ip превышено максимальное количествово демо пользователей. Попробуйте позже!']);
        }

        $demoUserService = new DemoUserService();
        $user = $demoUserService->createUser($request->ip());
        $demoUserService->createPurchases($user);

        Auth::login($user, true);

        return redirect(route('purchase.list'));
    }
}
