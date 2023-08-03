<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionTimezoneEnum: int
{
    use EnumTrait;

    case america = 1;
    case europe = 2;
    case africa = 3;
    case asia = 4;
    case australia = 5;
    case indian_ocean = 6;
    case pacific_ocean = 7;
    case atlantic_ocean = 8;
    case antarctica = 9;

    public static function getTitle($enum): string
    {
        return match ($enum) {
            self::america => 'آمریکا',
            self::europe => 'اروپا',
            self::africa => 'آفریقا',
            self::asia => 'آسیا',
            self::australia => 'استرالیا',
            self::indian_ocean => 'اقیانوس هند',
            self::pacific_ocean => 'اقیانوس آرام',
            self::atlantic_ocean => 'اقیانوس اطلس',
            self::antarctica => 'قطب جنوب',
            default => $enum->name,
        };
    }
}
