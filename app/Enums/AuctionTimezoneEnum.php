<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionTimezoneEnum: int
{
    use EnumTrait;

    case america = 1;
    case europe = 2;
    case africa = 3;
    case asia = 4;
    case australia = 5;
    case indian_ocean = 6;
    case pacific_ocean = 7;
    case atlantic_ocean = 8;
    case antarctica = 9;
}
