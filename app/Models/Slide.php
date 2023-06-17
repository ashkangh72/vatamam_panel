<?php

namespace App\Models;

use App\Enums\SlideGroupEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'boolean',
        'group' => SlideGroupEnum::class
    ];

    /**
     * @return BelongsTo|null
     */
    public function linkable()
    {
        if ($this->linkable_type && $this->linkable_id) {
            return $this->belongsTo($this->linkable_type, 'linkable_id');
        }

        return null;
    }
}
