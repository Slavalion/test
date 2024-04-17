<?php

namespace App\Actions;

use App\Enums\ReviewComplaintStatus;
use App\Models\ReviewComplaintGroup;
use App\Models\Transaction;
use App\Models\User;

class ReviewComplaintGroupRemoveAction
{
    /**
     * Execute the action.
     */
    public function handle($reviewComplaintGroupID)
    {
        $reviewComplaintGroup = ReviewComplaintGroup::findOrFail($reviewComplaintGroupID);

        if ($reviewComplaintGroup->user->role != User::ROLE_ADMIN) {
            $complaintPrice = $reviewComplaintGroup->transaction->amount;
            $reviewComplaintGroup->transaction->delete();

            $userTopUpBalanceAction = new UserTopUpBalance();
            $userTopUpBalanceAction->handle($reviewComplaintGroup->user, $complaintPrice);
        }

        // $groupHasCompletedItem = false;

        foreach ($reviewComplaintGroup->reviewComplaints as $reviewComplaint) {
            //     $complaintPrice = $reviewComplaintGroup->total * 8 * 100;

            //     if ($reviewComplaint->status == ReviewComplaintStatus::DONE) {
            //         $groupHasCompletedItem = true;
            //     } else {
            //         $userTopUpBalanceAction = new UserTopUpBalance();

            //         if ($reviewComplaintGroup->user->role != User::ROLE_ADMIN) {
            //             Transaction::create([
            //                 'user_id' => $reviewComplaintGroup->user->id,
            //                 'target_id' => $reviewComplaint->id,
            //                 'amount' => $complaintPrice,
            //                 'type' => Transaction::TYPE_TOP_UP,
            //                 'target' => Transaction::TARGET_RETURN_PURCHASE,
            //             ]);

            //             $userTopUpBalanceAction->handle($reviewComplaintGroup->user, $complaintPrice);
            //         }

            //         echo $reviewComplaint->task_id.PHP_EOL;
            //     }
            $reviewComplaint->delete();
        }

        $reviewComplaintGroup->delete();

        // if (! $groupHasCompletedItem) {
        //     $reviewComplaintGroup->delete();
        // }
    }
}
