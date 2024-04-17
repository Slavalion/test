<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserPriceType;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SettingController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $settings = Setting::all();

        $mappedSettings = $settings->mapWithKeys(function (Setting $item, int $key) {
            $itemValue = match ($item['type']) {
                'boolean' => boolval($item['value']),
                'price' => intval($item['value']) / 100,
                default => $item['value']
            };

            return [$item['key'] => $itemValue];
        });

        return Inertia::render('Admin/SettingsPage', [
            'settings' => $mappedSettings,
        ]);
    }

    public function set(Request $request): JsonResponse
    {
        Setting::updateOrCreate(['key' => $request->key], ['value' => $request->value, 'type' => 'boolean']);

        return response()->json();
    }

    public function setPrices(Request $request): JsonResponse
    {
        $prices = [];

        foreach ($request->all() as $key => $value) {
            $priceType = UserPriceType::tryFrom($key);

            if (! $priceType) {
                continue;
            }

            $prices[] = [
                'key' => 'price.'.$priceType->value,
                'value' => (int) $value * 100,
                'type' => 'price',
            ];
        }

        Setting::upsert($prices, ['key']);

        return response()->json();
    }
}
