<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'notifications' => $request->user()->preferences['notifications'] ?? [],
            // 'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            // 'wallets' => $request->user()->wallets()->where('status', 1)->get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateTgPaymentData(ProfileUpdateRequest $request): JsonResponse
    {
        $response = Http::get('https://api.telegram.org/bot'.config('mpbtop.telegram_payment_bot').'/sendMessage', [
            'chat_id' => $request->paymentChatId,
            'text' => 'Проверка подключения платежного бота',
        ]);

        if ($response->status() != 200) {
            return response()->json([
                'message' => 'Проверьте подключение бота',
            ], 422);
        }

        $userPreferences = $request->user()->preferences;

        $request->user()->preferences = [
            ...$userPreferences,
            ...$request->only('paymentChatId'),
        ];

        $request->user()->save();

        return response()->json([
            'message' => 'Платежные данные успешно сохранены',
        ]);
    }

    public function generateApiKey(Request $request): JsonResponse
    {
        $user = $request->user();

        $apiKey = Str::random(32);

        while (User::where('api_key', $apiKey)->exists()) {
            $apiKey = Str::random(32);
        }

        $user->api_key = $apiKey;
        $user->save();

        return response()->json(['api_key' => $apiKey]);
    }

    public function generateRefCode(Request $request): JsonResponse
    {
        $user = $request->user();

        $refCode = Str::random(16);

        while (User::where('ref_code', $refCode)->exists()) {
            $refCode = Str::random(16);
        }

        $user->ref_code = $refCode;
        $user->save();

        return response()->json(['ref_code' => $refCode]);
    }

    public function setNotifications(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->preferences = [
            ...$user->preferences,
            'notifications' => $request->notifications,
        ];

        $user->save();

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
