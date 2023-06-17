<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum WalletHistoryEnum: int
{
    use EnumTrait;

    case deposit = 1;
    case withdraw = 2;
    case payment = 3;
}
