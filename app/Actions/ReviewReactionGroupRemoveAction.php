<?php

namespace App\Actions;

use App\Enums\ReviewReactionStatus;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserRole;
use App\Models\ReviewReactionGroup;
use App\Models\Transaction;

class ReviewReactionGroupRemoveAction
{
    /**
     * Execute the action.
     */
    public function handle($reviewReactionGroupID)
    {
        $reviewReactionGroup = ReviewReactionGroup::findOrFail($reviewReactionGroupID);

        if ($reviewReactionGroup->user->role != UserRole::ADMIN) {
            $reviewReactionGroup->transaction->delete();
        }

        $groupHasCompletedItem = false;

        foreach ($reviewReactionGroup->reviewReactions as $reviewReaction) {
            $reactionPrice = $reviewReactionGroup->total * 8 * 100;

            if ($reviewReaction->status == ReviewReactionStatus::DONE) {
                $groupHasCompletedItem = true;
            } else {
                $userTopUpBalanceAction = new UserTopUpBalance();

                if ($reviewReactionGroup->user->role != UserRole::ADMIN) {
                    Transaction::create([
                        'user_id' => $reviewReactionGroup->user->id,
                        'target_id' => $reviewReaction->id,
                        'amount' => $reactionPrice,
                        'type' => TransactionType::TOP_UP,
                        'target' => TransactionTarget::RETURN_PURCHASE,
                    ]);

                    $userTopUpBalanceAction->handle($reviewReactionGroup->user, $reactionPrice);
                }

                echo $reviewReaction->task_id.PHP_EOL;
                $reviewReaction->delete();
            }
        }

        if (! $groupHasCompletedItem) {
            $reviewReactionGroup->delete();
        }
    }
}
