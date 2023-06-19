<?php

namespace App\Models;

use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Wallet extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(WalletHistory::class);
    }

    public function balance()
    {
        $totalDeposit = $this->histories()
            ->where('success', true)
            ->whereIn('type', [WalletHistoryTypeEnum::admin_deposit, WalletHistoryTypeEnum::deposit])
            ->sum('amount');

        $totalWithdraw = $this->histories()
            ->where('success', true)
            ->where('type', WalletHistoryTypeEnum::withdraw)
            ->sum('amount');

        return $totalDeposit - $totalWithdraw;
    }

    public function refreshBalance()
    {
        $this->balance = $this->balance();
        $this->save();
    }
}
