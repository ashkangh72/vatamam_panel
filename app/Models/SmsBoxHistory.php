<?php

namespace App\Models;

use App\Enums\{SmsBoxHistoryTypeEnum, WalletHistoryTypeEnum};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SmsBoxHistory extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => SmsBoxHistoryTypeEnum::class
    ];

    public function smsBox(): BelongsTo
    {
        return $this->belongsTo(SmsBox::class);
    }

    public function scopeSuccess($query)
    {
        return $query->where('success', true);
    }

    public function payUsingWallet($wallet, $price): bool
    {
        $smsBox = auth()->user()->smsBox;

        if ($wallet->balance() >= $price) {
            DB::transaction(function () use ($wallet, $smsBox, $price) {
                $wallet->histories()->create([
                    'type' => WalletHistoryTypeEnum::withdraw,
                    'amount' => $price,
                    'balance' => $wallet->balance() - $price,
                    'description' => 'پرداخت شارژ سیستم اطلاع رسانی پیامکی',
                    'success' => true,
                ]);
                $wallet->refreshBalance();

                $this->success = true;
                $this->save();

                $smsBox->refreshBalance();
            });

            return true;
        }

        return false;
    }
}
