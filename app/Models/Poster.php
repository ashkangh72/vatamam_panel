<?php

namespace App\Models;

use App\Enums\PosterGroupEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poster extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
        'group' => PosterGroupEnum::class
    ];

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
