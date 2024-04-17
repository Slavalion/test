<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BalanceController extends Controller
{
    public function fill(Request $request): JsonResponse
    {
        $amount = (int) $request->amount;
        $amount *= 100;

        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->total = $amount;
        $order->payment_type = 'tinkoff';
        $order->save();

        $receipt = [
            'Email' => 'support@mpb.top',
            'Taxation' => 'usn_income',
            'Items' => [
                [
                    'Name' => 'Пополнение баланса',
                    'Quantity' => '1',
                    'Amount' => $amount,
                    'Price' => $amount,
                    'Tax' => 'none',
                ],
            ]
        ];

        $response = Http::post('https://securepay.tinkoff.ru/v2/Init', [
            'TerminalKey' => config('payments.tinkoff.terminal_key'),
            'Amount' => $amount,
            'OrderId' => $order->id,
            'Description' => '',
            'Receipt' => $receipt
        ]);

        $tinkoffResponse = $response->json();

        if ($tinkoffResponse['Status'] == 'NEW') {
            $order->payment_id = $tinkoffResponse['PaymentId'];
            $order->save();

            return response()->json([
                'redirect_to' => $tinkoffResponse['PaymentURL']
            ]);
        }
    }
}
