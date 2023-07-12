<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SafeBoxHistoryTypeEnum: int
{
    use EnumTrait;

    case auction_guarantee = 1;
    case order = 2;
    case checkout = 3;
}
