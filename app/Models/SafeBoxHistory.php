<?php

namespace App\Models;

use App\Enums\SafeBoxHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class SafeBoxHistory extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => SafeBoxHistoryTypeEnum::class
    ];

    public function safeBox(): BelongsTo
    {
        return $this->belongsTo(SafeBox::class);
    }

    public function historiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeSuccess($query)
    {
        return $query->where('success', true);
    }
}
