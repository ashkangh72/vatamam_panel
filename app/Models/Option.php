<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function getValue($key)
    {
        $option = self::where('name', $key)->first();

        return $option ? $option->value : null;
    }
}
