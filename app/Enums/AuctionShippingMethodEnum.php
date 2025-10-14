<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionShippingMethodEnum: int
{
    use EnumTrait;

    case post = 1;
    case tipax = 2;
    case train = 3;
    case plain = 4;
    case driving = 5;
    case freight = 6;
}
