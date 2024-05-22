<?php

namespace App\Actions;

use App\Models\BotResponseLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class BotRequestAction
{
    public function execute($rowsData)
    {
        if (config('mpbtop.demo_mode')) {
            return;
        }

        $json = json_encode($rowsData);

        try {
            $response = Http::withBody($json, 'application/json')->post(config('mpbtop.bot_url'), $rowsData);
        } catch (\Throwable $th) {

            // Setting::updateOrCreate(['key' => 'maintenance_mode'], ['value' => true, 'type' => 'boolean']);

            (new SendAdminNotificationAction())->handle("#BOT_REQUEST_PROBLEM \n\n При запросе к боту возникла ошибка");
            (new SendAdminNotificationAction())->handle("#BOT_REQUEST_PROBLEM \n\n ".$th->getMessage());

            BotResponseLog::create([
                'request' => $json,
                'response' => $th->getMessage(),
            ]);

            $response = null;
        }

        return $response;
    }
}
