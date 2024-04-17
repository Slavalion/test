<?php

namespace App\Actions;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class BotRequestAction
{
    public function execute($rowsData)
    {
        if (config('mpbtop.demo_mode')) {
            return;
        }

        try {
            $response = Http::withBody(json_encode($rowsData), 'application/json')->post(config('mpbtop.bot_url'), $rowsData);
        } catch (\Throwable $th) {

            // Setting::updateOrCreate(['key' => 'maintenance_mode'], ['value' => true, 'type' => 'boolean']);

            // (new SendAdminNotificationAction())->handle("ОПОВЕЩЕНИЕ АДМИНИСТРАТОРАМ \n\nБот не отвечает, включен режим техобслуживания");

            $response = null;
        }

        return $response;
    }
}
