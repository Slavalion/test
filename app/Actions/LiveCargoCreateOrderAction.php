<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class LiveCargoCreateOrderAction
{
    public function handle($routes)
    {
        $preparedData = [
            'route' => $routes,
            'date' => now()->format('Y-m-d\Th:i:s\ZO'),
        ];

        if (config('mpbtop.livecargo.testmode')) {
            $preparedData['isTest'] = true;
        }

        return Http::withBody(json_encode($preparedData), 'application/json')
            ->withHeaders([
                'Authorization' => 'Bearer '.config('mpbtop.livecargo.api-key'),
            ])->post('https://livecargo.ru/server-api/api/public/order');
    }
}
