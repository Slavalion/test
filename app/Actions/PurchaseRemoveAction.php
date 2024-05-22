<?php

namespace App\Actions;

use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Enums\UserRole;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Services\UserService;

class PurchaseRemoveAction
{
    /**
     * Execute the action.
     */
    public function handle(Purchase $purchase)
    {
        $userTopUpBalanceAction = new UserTopUpBalance();

        $userService = new UserService($purchase->user);
        $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

        if ($purchase->user->role != UserRole::ADMIN) {
            Transaction::create([
                'user_id' => $purchase->user->id,
                'target_id' => $purchase->id,
                'amount' => $purchasePrice,
                'type' => TransactionType::TOP_UP,
                'target' => TransactionTarget::RETURN_PURCHASE,
            ]);

            $userTopUpBalanceAction->handle($purchase->user, $purchasePrice);
        }

        echo $purchase->task_id.PHP_EOL;

        $purchase->delete();
    }
}
