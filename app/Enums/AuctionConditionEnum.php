<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionConditionEnum: int
{
    use EnumTrait;

    case new = 1;
    case used = 2;
    case antique = 3;
    case luxury = 4;
}
