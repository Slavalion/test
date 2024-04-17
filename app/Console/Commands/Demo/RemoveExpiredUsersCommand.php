<?php

namespace App\Console\Commands\Demo;

use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveExpiredUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:remove-expired-users-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DEMO: Remove expired users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Remove expired demo users');

        if (! config('mpbtop.demo_mode')) {
            return;
        }

        $expiredUsers = User::where('created_at', '<=', now()->subHour()->toDateTimeString())->get();

        DB::beginTransaction();

        foreach ($expiredUsers as $expiredUser) {
            Task::where('user_id', $expiredUser->id)->delete();
            Purchase::where('user_id', $expiredUser->id)->delete();
            PurchaseGroup::where('user_id', $expiredUser->id)->delete();

            $expiredUser->delete();
        }

        DB::commit();
    }
}
