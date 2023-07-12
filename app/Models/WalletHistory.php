<?php

namespace App\Models;

use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletHistory extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => WalletHistoryTypeEnum::class
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

}
