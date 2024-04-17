<?php

namespace App\Enums;

enum ReviewReactionPeriod: int
{
    case THREE_HOURS = 3;
    case TWELVE_HOURS = 12;
    case DAY = 24;
    case WEEK = 168;
    case TWO_WEEKS = 336;
}
