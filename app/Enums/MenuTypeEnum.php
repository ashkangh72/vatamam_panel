<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum MenuTypeEnum: int
{
    use EnumTrait;

    case normal = 1;
    case category = 2;
}
