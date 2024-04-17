<?php

namespace App\Console\Commands;

use App\Actions\BotRequestAction;
use App\Models\Purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RecheckDeliveryStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpb:recheck-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $purchases = Purchase::select('task_id')
            ->whereIn('delivery_status', [
                Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP,
                Purchase::DELIVERY_STATUS_ON_THE_WAY,
                Purchase::DELIVERY_STATUS_PENDING,
                Purchase::DELIVERY_STATUS_SORTED,
                Purchase::DELIVERY_STATUS_PROCESSING,
                Purchase::DELIVERY_STATUS_NONE,
            ])
            ->where('status', 'done')
            ->get();

        // $toFile = '';
        // foreach ($purchases->pluck('task_id') as $value) {
        //     $toFile .= $value.PHP_EOL;
        // }
        // Storage::put('to_recheck.txt',$toFile);

        $botRequestAction = new BotRequestAction();
        $botRequestAction->execute([
            'type' => 'wb-recheck-delivery-statuses',
            'task_ids' => $purchases->pluck('task_id')->toArray(),
        ]);
    }
}
