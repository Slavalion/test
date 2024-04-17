<?php

namespace App\Enums;

enum BotRequestLogStatus: int
{
    case DEFAULT = 0;
    case SUCCESS = 1;
    case FAIL = -1;
}
