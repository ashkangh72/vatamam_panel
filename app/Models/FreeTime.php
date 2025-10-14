<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\{FreeTimeDayEnum, FreeTimeTypeEnum};

class FreeTime extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => FreeTimeTypeEnum::class,
        'day' => FreeTimeDayEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
