<?php

namespace App\Models;

use App\Enums\SmsBoxHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class SmsBox extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SmsBoxHistory::class);
    }

    public function balance()
    {
        $totalDeposit = $this->histories()
            ->where('type', SmsBoxHistoryTypeEnum::deposit)
            ->success()
            ->sum('amount');

        $totalWithdraw = $this->histories()
            ->where('type', SmsBoxHistoryTypeEnum::withdraw)
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
