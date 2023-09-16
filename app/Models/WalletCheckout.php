<?php

namespace App\Models;

use App\Enums\WalletCheckoutStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletCheckout extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => WalletCheckoutStatusEnum::class
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
