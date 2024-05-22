<?php

namespace App\Console\Commands;

use App\Actions\UserTopUpBalance;
use App\Enums\PickUpStatus;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Models\PickUp;
use App\Models\Transaction;
use App\Services\UserService;
use Illuminate\Console\Command;

class PickUpReturnsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pick-up:return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Возврат незабранных заборов';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userTopUpBalance = new UserTopUpBalance();
        $failurePickUps = PickUp::whereIn('status', [PickUpStatus::PROCESS, PickUpStatus::FAIL_PICKUP])
            ->with('user')
            ->get();

        foreach ($failurePickUps as $pickUp) {
            $userService = new UserService($pickUp->user);
            $purchasePrice = $userService->getPrice(UserPriceType::PICK_UP);

            Transaction::create([
                'user_id' => $pickUp->user->id,
                'target_id' => $pickUp->id,
                'amount' => $purchasePrice,
                'type' => TransactionType::TOP_UP,
                'target' => TransactionTarget::RETURN_PICK_UP,
            ]);

            $userTopUpBalance->handle($pickUp->user, $purchasePrice);

            $pickUp->status = PickUpStatus::FAIL;
            $pickUp->save();
        }
    }
}
