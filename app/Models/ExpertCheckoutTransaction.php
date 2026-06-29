<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertCheckoutTransaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'records' => 'array',
    ];

    public function expertCheckout(): BelongsTo
    {
        return $this->belongsTo(ExpertCheckout::class);
    }
}
