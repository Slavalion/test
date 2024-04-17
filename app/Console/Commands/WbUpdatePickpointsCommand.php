<?php

namespace App\Console\Commands;

use App\Models\WbPickpoints;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WbUpdatePickpointsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:update-pickpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update wildberries pickpoints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://static-basket-01.wb.ru/vol0/data/all-poo-fr-v7.json');
        $respJson = $response->json();

        WbPickpoints::query()->truncate();

        foreach ($respJson[0]['items'] as $pickpoint) {
            if (isset($pickpoint['isExternalPostamat'])) {
                // Постаматы пропускаем
                continue;
            }

            WbPickpoints::updateOrCreate([
                'pickpoint_id' => $pickpoint['id'],
            ], [
                'coordinates' => implode(',', $pickpoint['coordinates']),
                'work_time' => isset($pickpoint['workTime']) ? $pickpoint['workTime'] : '',
                'address' => preg_replace('/\s+/', ' ', trim($pickpoint['address'])),
            ]);
        }

        // Import wb`s pickpoints
        $resp = [];

        foreach (WbPickpoints::limit(100000)->get() as $pickpoint) {
            $resp[] = [
                'type' => 'Feature',
                'id' => $pickpoint->pickpoint_id,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => explode(',', $pickpoint->coordinates),
                ],
                'properties' => [
                    'balloonContentWorkTime' => $pickpoint->work_time,
                    'balloonContentAddress' => $pickpoint->address,
                    //    "clusterCaption" => "<strong><s>Еще</s> одна</strong> метка",
                    'hintContent' => $pickpoint->address,
                ],
            ];
        }

        Storage::disk('public')->put('pickpoints.json', json_encode($resp));
    }
}
