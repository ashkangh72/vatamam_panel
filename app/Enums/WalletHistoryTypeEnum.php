<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum WalletHistoryTypeEnum: int
{
    use EnumTrait;

    case deposit = 1;
    case withdraw = 2;
    case admin_deposit = 3;
    case admin_withdraw = 4;
    case income = 5;
    case refund = 6;
}
