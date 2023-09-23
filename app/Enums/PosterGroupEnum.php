<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum PosterGroupEnum: int
{
    use EnumTrait;

    case poster = 1;
}
