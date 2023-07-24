<?php

namespace App\Models;

use App\Enums\MenuTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Menu extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => MenuTypeEnum::class,
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'menuable_id', 'id');
    }
}
