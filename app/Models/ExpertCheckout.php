<?php

namespace App\Models;

use App\Enums\ExpertCheckoutStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertCheckout extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ExpertCheckoutStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
