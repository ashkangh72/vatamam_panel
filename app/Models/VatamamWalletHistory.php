<?php

namespace App\Models;

use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VatamamWalletHistory extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => WalletHistoryTypeEnum::class
    ];

    public function scopeSuccess($query)
    {
        return $query->where('success', true);
    }
}
