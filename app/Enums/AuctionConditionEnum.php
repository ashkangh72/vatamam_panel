<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionConditionEnum: int
{
    use EnumTrait;

    case new = 1;
    case used = 2;
    case antique = 3;
    case luxury = 4;

    public static function getTitle($enum): string
    {
        return match ($enum) {
            self::new => 'کالای نو',
            self::used => 'کالای دست دوم',
            self::antique => 'کالای آنتیک',
            self::luxury => 'کالای لوکس',
            default => $enum->name,
        };
    }
}
