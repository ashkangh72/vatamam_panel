<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum FreeTimeTypeEnum: int
{
    use EnumTrait;

    case day = 1;
    case date = 2;
}
