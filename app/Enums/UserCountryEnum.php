<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum UserCountryEnum: int
{
    use EnumTrait;

    case iran = 1;
    case iraq = 2;
    case emirates = 3;
    case turkey = 4;
    case england = 5;
}
