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
            ->whereIn('type', [WalletHistoryTypeEnum::admin_deposit, WalletHistoryTypeEnum::deposit, WalletHistoryTypeEnum::income])
            ->success()
            ->sum('amount');

        $totalWithdraw = $this->histories()
            ->whereIn('type', [WalletHistoryTypeEnum::withdraw, WalletHistoryTypeEnum::admin_withdraw])
            ->success()
            ->sum('amount');

        return $totalDeposit - $totalWithdraw;
    }

    public function refreshBalance()
    {
        $this->balance = $this->balance();
        $this->save();
    }
}
