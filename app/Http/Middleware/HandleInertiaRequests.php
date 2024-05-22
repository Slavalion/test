<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $demoMode = config('mpbtop.demo_mode') ? ['demo_mode' => true] : [];

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => function () use ($request) {
                    if (! $request->user()) {
                        return [];
                    }

                    if ($request->user()?->role === UserRole::CLIENT) {
                        return $request->user()->makeHidden('role');
                    }

                    return $request->user();
                },
                'wallets' => function () use ($request) {
                    if (! $request->user()) {
                        return [];
                    }

                    return Wallet::where('user_id', $request->user()->id)->select(['id', 'balance', 'updated_at'])->get();
                },
            ],
            'prices' => config('mpbtop.prices'),
            'data' => [
                'deliveries' => Purchase::availableForPickUp()
                    ->where('user_id', $request->user()?->id)
                    ->count(),
                'availableReviews' => Purchase::availableReviews()
                    ->where('user_id', $request->user()?->id)
                    ->count(),

            ],
            'settings' => [
                ...Setting::all()->mapWithKeys(function (Setting $item, int $key) {
                    $itemValue = match ($item['type']) {
                        'boolean' => boolval($item['value']),
                        default => $item['value']
                    };

                    return [$item['key'] => $itemValue];
                }),
                ...$demoMode,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
