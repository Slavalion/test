<?php

namespace App\Enums;

use App\Models\ActionCart;
use App\Models\ActionCartGroup;
use App\Models\ActionSearch;
use App\Models\ActionSearchGroup;
use App\Models\PickUp;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\ReviewComplaint;
use App\Models\ReviewComplaintGroup;
use App\Models\ReviewReaction;
use App\Models\ReviewReactionGroup;

enum TransactionTarget: string
{
    case ACTION_CART_GROUP = ActionCartGroup::class;
    case ACTION_CART = ActionCart::class;
    case ACTION_SEARCH_GROUP = ActionSearchGroup::class;
    case BALANCE_MANUALLY = 'balance_manually';
    case ACTION_SEARCH = ActionSearch::class;
    case PURCHASE = PurchaseGroup::class;
    case PURCHASE_ITEM = Purchase::class;
    case REVIEW = 'review';
    case REVIEW_COMPLAINT = ReviewComplaint::class;
    case REVIEW_COMPLAINT_GROUP = ReviewComplaintGroup::class;
    case REVIEW_REACTION = ReviewReaction::class;
    case PICK_UP = PickUp::class;
    case RETURN_PICK_UP = 'return_pickup';
    case REVIEW_REACTION_GROUP = ReviewReactionGroup::class;
    case LIVECARGO = 'livecargo';
    case RETURN_PURCHASE = 'return_purchase';
    case RETURN_REVIEW = 'return_review';
    case TINKOFF = 'tinkoff';
    case OLD_DEFAULT_VALUE = '';
}
