<?php

namespace App\Models;

use App\Enums\SlideGroupEnum;
use App\Observers\SlideObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
        'group' => SlideGroupEnum::class
    ];

    protected static function booted(): void
    {
        static::observe(SlideObserver::class);
    }

    /**
     * @return BelongsTo|null
     */
    public function linkable(): ?BelongsTo
    {
        if ($this->linkable_type && $this->linkable_id) {
            return $this->belongsTo($this->linkable_type, 'linkable_id');
        }

        return null;
    }

    public function getImageAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }
}
