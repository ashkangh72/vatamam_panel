<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TicketStatusEnum: int
{
    use EnumTrait;

    case new = 1;
    case admin_answer = 2;
    case user_answer = 3;
    case closed = 4;
}
