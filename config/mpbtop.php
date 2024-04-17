<?php

return [
    'demo_mode' => env('APP_DEMO_MODE', false),
    'bot_url' => env('MPBTOP_BOT_URL', '127.0.0.1'),
    'prices' => [
        'purchase' => 70,
        'review' => 75,
    ],
    'livecargo' => [
        'api-key' => env('LIVECARGO_API_KEY', ''),
        'testmode' => false,
        'prices' => [
            'small-sized' => 50,
            'large-sized' => 100,
        ],
    ],
    'telegram_payment_bot' => env('TELEGRAM_PAYMENT_BOT', ''),
];
