<?php

namespace App\Enums;

enum PurchaseDeliveryStatus: string
{
    case NONE = 'none';
    case PROCESSING = 'processing';
    case SORTED = 'sorted';
    case PENDING = 'pending';
    case ON_THE_WAY = 'on_the_way';
    case AVAILABLE_FOR_PICK_UP = 'available_for_pick_up';
    case PICKED_UP = 'picked_up';
}
