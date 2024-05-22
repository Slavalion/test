<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('devtest')->everyMinute();
        $schedule->command('livecargo:create-order')->dailyAt('11:20');
        $schedule->command('pick-up:return')->dailyAt('1:00');
        $schedule->command('wb:update-pickpoints')->dailyAt('5:00');
        $schedule->command('mpb:recheck-statuses')->everyTwoHours();

        $schedule->command('mpb:reset-courier')->dailyAt('00:00');

        // Only for demo
        if (config('mpbtop.demo_mode')) {
            $schedule->command('demo:remove-expired-users')->everyFiveMinutes();
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
