<?php

namespace App\Enums;

enum WalletStatus: int
{
    case PENDING = 0;
    case READY = 1;
    case LIMIT_REACHED = 2;
    case LOGED_OUT = 3;
    case NEED_VERIF = 4;
}
