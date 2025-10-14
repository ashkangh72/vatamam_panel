<?php

namespace App\Models;

use App\Enums\NotificationSettingKeyEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'key' => NotificationSettingKeyEnum::class,
        'push' => 'boolean',
        'email' => 'boolean',
        'sms' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getKeyTitle(NotificationSettingKeyEnum $key): ?string
    {
        return match ($key) {
            // seller
            NotificationSettingKeyEnum::order_paid => 'اطلاع به فروشنده هنگام پرداخت مزایده',
            NotificationSettingKeyEnum::auction_end => 'دریافت پیام هنگام پایان مزایده',
            NotificationSettingKeyEnum::auction_before_end => 'دریافت پیام نیم ساعت قبل از پایان مزایده',

            // buyer
            NotificationSettingKeyEnum::thanks_for_buy => 'تشکر از خرید بعد از پرداخت',
            NotificationSettingKeyEnum::winning_auction => 'دریافت پیام هنگام برنده شدن مزایده',
            NotificationSettingKeyEnum::auction_higher_bid => 'دریافت پیام هنگام ثبت شدن پیشنهاد بالاتر',
            NotificationSettingKeyEnum::followed_auction => 'هشدار دقایق پایانی مزایده (دنبال کردن مزایده)',

            // system
            NotificationSettingKeyEnum::events => 'مناسبت ها',
            NotificationSettingKeyEnum::news => 'اخبار',
            NotificationSettingKeyEnum::favorites => 'مزایده های مورد علاقه',
            NotificationSettingKeyEnum::transactions => 'تراکنش های مالی',
            NotificationSettingKeyEnum::discounts => 'دریافت بن تخفیف',
        };
    }
}
