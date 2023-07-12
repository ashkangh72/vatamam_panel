<?php

namespace App\Models;

use App\Enums\SafeBoxHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class SafeBox extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SafeBoxHistory::class);
    }

    public function balance()
    {
        $totalDeposit = $this->histories()
            ->where('success', true)
            ->whereIn('type', [SafeBoxHistoryTypeEnum::auction_guarantee->value, SafeBoxHistoryTypeEnum::order->value])
            ->sum('amount');

        $totalWithdraw = $this->histories()
            ->where('success', true)
            ->where('type', SafeBoxHistoryTypeEnum::checkout->value)
            ->sum('amount');

        return $totalDeposit - $totalWithdraw;
    }

    public function refreshBalance()
    {
        $this->balance = $this->balance();
        $this->save();
    }
}
