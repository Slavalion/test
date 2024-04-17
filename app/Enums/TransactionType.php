<?php

namespace App\Enums;

enum TransactionType: int
{
    case TOP_UP = 1;
    case WRITE_OFF = -1;
}
