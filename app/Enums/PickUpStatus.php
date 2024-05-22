<?php

namespace App\Enums;

enum PickUpStatus: int
{
    case PENDING = 0;
    case PROCESS = 1;
    case DONE = 2;

    // Не обработан
    case FAIL = -1;

    // Не смогли получить
    case FAIL_PICKUP = -10;
    case NOT_ENOUGH_BALANCE = -2;
    case NOT_FOUND_ADDRESS = -3;
    case UNSUPPORTED_ADDRESS = -4;
}
