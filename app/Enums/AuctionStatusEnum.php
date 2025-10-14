<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionStatusEnum: int
{
    use EnumTrait;

    case pending_approval = 1;
    case approved = 2;
    case rejected = 3;
}
