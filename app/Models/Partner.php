<?php

namespace App\Models;

use App\Enums\PartnerStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => PartnerStatusEnum::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDocumentAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }
}
