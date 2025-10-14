<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum PartnerStatusEnum: int
{
    use EnumTrait;

    case pending = 1;
    case accepted = 2;
    case rejected = 3;
}
