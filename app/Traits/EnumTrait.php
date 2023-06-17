<?php

namespace App\Traits;

trait EnumTrait
{
    public static function find($name): ?object
    {
        $enumCollection = collect(self::cases());

        return $enumCollection->where('name', $name)->first() ?? null;
    }
}
