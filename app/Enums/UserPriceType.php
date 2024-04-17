<?php

namespace App\Enums;

enum UserPriceType: string
{
    case PURCHASE = 'purchase';
    case REVIEW = 'review';
    case PICK_UP = 'pick_up';
    case REVIEW_REACTION = 'review_reaction';
    case REVIEW_COMPLAINT = 'review_complaint';
    case ACTION_CART = 'action_cart';
    case ACTION_SEARCH = 'action_search';
}
