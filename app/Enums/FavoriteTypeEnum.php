<?php

namespace App\Enums;

use App\Models\{Auction, User};
use App\Traits\EnumTrait;

enum FavoriteTypeEnum: string
{
    use EnumTrait;

    case auction = Auction::class;
    case user = User::class;
}
