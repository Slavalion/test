<?php

namespace App\Enums;

enum UserRole: int
{
    case CLIENT = 0;
    case ADMIN = 1;
    case COURIER = 2;
    case COURIER_ADMIN = 3;
    case MANAGER = 4;
}
