<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;

class ResetCourierStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpb:reset-courier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset courier is_active_courier preference';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $couriers = User::where('role', UserRole::COURIER)->get();

        foreach ($couriers as $courier) {
            $courier->preferences = [
                ...$courier->preferences,
                'is_active_courier' => false,
            ];

            $courier->save();
        }
    }
}
