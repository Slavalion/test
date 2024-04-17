<?php

namespace App\Actions;

use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\Transaction;
use App\Models\User;
use App\Services\UserService;

class PurchaseGroupRemoveAction
{
    /**
     * Execute the action.
     */
    public function handle($purchaseGroupID)
    {
        $purchaseGroup = PurchaseGroup::findOrFail($purchaseGroupID);

        if ($purchaseGroup->user->role != User::ROLE_ADMIN) {
            $purchaseGroup->transaction->delete();
        }

        $groupHasCompletedItem = false;

        foreach ($purchaseGroup->purchases as $purchase) {
            $userService = new UserService($purchaseGroup->user);
            $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

            if ($purchase->status == Purchase::STATUS_DONE) {
                $groupHasCompletedItem = true;
            } elseif ($purchase->status == Purchase::STATUS_NOT_ENOUGH_W_BALANCE) {
                $groupHasCompletedItem = true;
            } else {
                $userTopUpBalanceAction = new UserTopUpBalance();

                if ($purchaseGroup->user->role != User::ROLE_ADMIN) {
                    Transaction::create([
                        'user_id' => $purchaseGroup->user->id,
                        'target_id' => $purchase->id,
                        'amount' => $purchasePrice,
                        'type' => TransactionType::TOP_UP,
                        'target' => TransactionTarget::RETURN_PURCHASE,
                    ]);

                    $userTopUpBalanceAction->handle($purchaseGroup->user, $purchasePrice);
                }

                echo $purchase->task_id.PHP_EOL;
                $purchase->delete();
            }
        }

        if (! $groupHasCompletedItem) {
            $purchaseGroup->delete();
        }
    }
}
