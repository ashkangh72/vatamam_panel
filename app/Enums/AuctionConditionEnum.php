<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuctionConditionEnum: int
{
    use EnumTrait;

    case collectible = 1;
    case decorative = 2;
    case used = 3;
    case repaired = 4;
    case need_repair = 5;
    case new_lux = 6;
    case other = 7;

    public static function getTitle($enum): string
    {
        return match ($enum) {
            self::collectible => 'آنتیک کلکسیونی',
            self::decorative => 'آنتیک دكوراتيو',
            self::used => 'آنتیک کاربردی',
            self::repaired => 'آنتیک مرمت شده',
            self::need_repair => 'آنتیک نیاز مند به تعمیر',
            self::new_lux => 'لوکس جدید',
            self::other => 'سایر',
            default => $enum->name,
        };
    }
}
