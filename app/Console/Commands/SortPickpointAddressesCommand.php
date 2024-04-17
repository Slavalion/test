<?php

namespace App\Console\Commands;

use App\Models\PickpointAddress;
use Illuminate\Console\Command;

class SortPickpointAddressesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sort-addresses';

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
        $handle = fopen(resource_path().'/sorted-addresses.csv', 'r');
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $sort = $data[0];
            for ($i = 1; $i < 9; $i++) {
                $address = $data[$i];
                if ($address != '') {
                    $pickpointAddress = PickpointAddress::where('address', $address)->first();

                    if (! $pickpointAddress) {
                        $this->info('Не найден адрес - '.$address);

                        continue;
                    }
                    $pickpointAddress->sort = $sort;
                    $pickpointAddress->save();
                }
            }
        }
    }
}
