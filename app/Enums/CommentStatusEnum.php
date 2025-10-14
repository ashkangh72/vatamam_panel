<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum CommentStatusEnum: int
{
    use EnumTrait;

    case pending = 1;
    case approved = 2;
    case rejected = 3;
}
