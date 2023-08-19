<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderStatusEnum: int
{
    use EnumTrait;

    case pending = 1;
    case paid = 2;
    case locked = 3;
    case canceled = 4;
}
