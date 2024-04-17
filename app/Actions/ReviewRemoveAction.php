<?php

namespace App\Actions;

use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Models\Review;
use App\Models\Transaction;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class ReviewRemoveAction
{
    /**
     * Execute the action.
     */
    public function handle(Review $review)
    {
        if (! $review) {
            return false;
        }

        $review->purchase->review_id = 0;

        DB::transaction(function () use ($review) {
            $review->delete();
            $review->purchase->save();
        });

        $user = $review->purchase->user;

        $userService = new UserService($user);
        $reviewPrice = $userService->getPrice(UserPriceType::REVIEW);

        Transaction::create([
            'user_id' => $user?->id,
            'amount' => $reviewPrice,
            'type' => TransactionType::TOP_UP,
            'target' => TransactionTarget::RETURN_REVIEW,
        ]);

        (new UserTopUpBalance())->handle($user, $reviewPrice);
    }
}
