<?php

namespace App\Models;

use App\Enums\WidgetKeyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Widget extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
        'key' => WidgetKeyEnum::class
    ];

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(WidgetOption::class);
    }

    public function option($key, $default = null)
    {
        $value = $this->options->where('key', $key)->first() ? $this->options->where('key', $key)->first()->value : $default;

        return is_numeric($value) ? (int)$value : $value;
    }
}
