<?php

namespace App\Enums;

enum ReviewComplaintStatus: int
{
    case PROCESS = 1;
    case DONE = 2;
    case ERROR = 3;
}
