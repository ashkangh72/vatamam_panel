<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum FreeTimeDayEnum: int
{
    use EnumTrait;

    case Saturday = 1;
    case Sunday = 2;
    case Monday = 3;
    case Tuesday = 4;
    case Wednesday = 5;
    case Thursday = 6;
    case Friday = 7;
}
