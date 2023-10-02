<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum SmsBoxHistoryTypeEnum: int
{
    use EnumTrait;

    case deposit = 1;
    case withdraw = 2;
}
