<?php

namespace App\Enums;

enum UserRole: int
{
    case CLIENT = 0;
    case ADMIN = 1;
    case CURIER = 2;
    case CURIER_ADMIN = 3;
}
