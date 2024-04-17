<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case UNAVAILABLE = 'unavailable';
    case NOT_ENOUGH_W_BALANCE = 'not_enough_w_balance';
    case DONE = 'done';
}
