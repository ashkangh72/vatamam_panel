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
    case england = 6;
    case oceania = 5;
    case not_specified = 7;

    public static function getTitle($enum): string
    {
        return match ($enum) {
            self::america => 'آمریکایی',
            self::europe => 'اروپایی',
            self::africa => 'آفریقایی',
            self::asia => 'آسیایی',
            self::england => 'انگلیسی',
            self::oceania => 'اقیانوسیه',
            self::not_specified => 'نامشخص',
            default => $enum->name,
        };
    }
}
