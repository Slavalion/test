<?php

namespace App\Enums;

enum LivecargoDeliveryPickedUpEnum: int
{
    case PICKED_UP = 1;
    case PENDING = 0;
    case FAILURE = -1;
}
