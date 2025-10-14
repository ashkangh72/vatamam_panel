<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionBidTypeEnum: int
{
    use EnumTrait;

    case bid = 1;
    case highest_bid = 2;
    case quick_sale_bid = 3;
    case partner_quick_sale_bid = 4;
}
