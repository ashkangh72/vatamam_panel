<?php

namespace App\Traits;

trait EnumTrait
{
    public static function find($name): ?object
    {
        $enumCollection = collect(self::cases());

        return $enumCollection->where('name', $name)->first() ?? null;
    }

    public static function getNames(): array
    {
        return collect(self::cases())->pluck('name')->toArray();
    }

    public static function getValues(array $names = null): array
    {
        if ($names) {
            return collect(self::cases())->whereIn('name', $names)->pluck('value')->toArray();
        }

        return collect(self::cases())->pluck('value')->toArray();
    }
}
