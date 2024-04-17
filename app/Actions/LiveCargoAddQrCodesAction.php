<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class LiveCargoAddQrCodesAction
{
    public function handle($deliveyID, $qrCodes)
    {
        $pendingRequest = Http::withHeaders([
            'Authorization' => 'Bearer '.config('mpbtop.livecargo.api-key'),
            // 'Content-type' => 'multipart/form-data; boundary=-------------'.uniqid(),
        ]);

        $pendingRequest->asMultipart();
        // $pendingRequest->buildBeforeSendingHandler();

        // dd($pendingRequest);

        return $pendingRequest->patch('https://livecargo.ru/server-api/api/public/order', [
            'id' => $deliveyID,
            ...$qrCodes,
        ]);
    }
}
