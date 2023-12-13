<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    protected $guarded = ['id'];

    const TYPES = [
        'VERIFY_CODE' => [
            'key'    => 'otp',
            'string' => 'کد تایید'
        ],
        'ORDER_PAID' => [
            'key'    => 'order_paid',
            'string' => 'اطلاع رسانی پرداخت سفارش به مدیر و فروشنده'
        ],
        'AUCTION_FIRST_BID' => [
            'key'    => 'auction_first_bid',
            'string' => 'اطلاع رسانی اولین پیشنهاد مزایده به فروشنده'
        ],
        'AUCTION_ACCEPT' => [
            'key'    => 'auction_accept',
            'string' => 'اطلاع رسانی تایید مزایده به فروشنده'
        ],
        'AUCTION_REJECT' => [
            'key'    => 'auction_reject',
            'string' => 'اطلاع رسانی رد مزایده به فروشنده'
        ],
        'AUCTION_END' => [
            'key'    => 'auction_end',
            'string' => 'یادآوری اتمام مزایده به فروشنده'
        ],
        'AUCTION_BEFORE_END' => [
            'key'    => 'auction_before_end',
            'string' => 'یادآوری مزایده پیش از اتمام به فروشنده'
        ],
        'THANKS_FOR_BUY' => [
            'key'    => 'thanks_for_buy',
            'string' => 'پیام تشکر از خرید به کاربر'
        ],
        'WINNING_AUCTION' => [
            'key'    => 'winning_auction',
            'string' => 'اطلاع رسانی برنده شدن مزایده به کاربر'
        ],
        'AUCTION_HIGHER_BID' => [
            'key'    => 'auction_higher_bid',
            'string' => 'اطلاع رسانی پیشنهاد بالاتر به کاربر'
        ],
        'FOLLOWED_AUCTION' => [
            'key'    => 'followed_auction',
            'string' => 'اطلاع رسانی مزایده دنبال شده به کاربر'
        ],
        'EVENTS' => [
            'key'    => 'events',
            'string' => 'اطلاع رسانی رویدادها به کاربر'
        ],
        'NEWS' => [
            'key'    => 'news',
            'string' => 'اطلاع رسانی اخبار به کاربر'
        ],
        'FAVORITES' => [
            'key'    => 'favorites',
            'string' => 'اطلاع رسانی علاقه مندی ها به کاربر'
        ],
        'TRANSACTIONS' => [
            'key'    => 'transactions',
            'string' => 'اطلاع رسانی تراکنش ها به کاربر'
        ],
        'DISCOUNTS' => [
            'key'    => 'discounts',
            'string' => 'اطلاع رسانی تخفیف ها به کاربر'
        ],
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        foreach (self::TYPES as $type) {
            if ($this->type == $type['key']) {
                return $type['string'];
            }
        }
    }
}
