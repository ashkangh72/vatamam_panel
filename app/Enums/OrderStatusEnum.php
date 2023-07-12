<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderStatusEnum: int
{
    use EnumTrait;

    case pending = 1;
    case paid = 2;
    case locked = 3;
    case sending = 4;
    case send_request = 5;
    case canceled = 6;
}
