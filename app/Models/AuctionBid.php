<?php

namespace App\Models;

use App\Enums\AuctionBidTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};

class AuctionBid extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_winner' => 'boolean',
        'type' => AuctionBidTypeEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function loser(): HasOne
    {
        return $this->hasOne(AuctionLoser::class, 'auction_bid_id');
    }

}
