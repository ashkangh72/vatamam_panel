<?php

namespace App\Models;

use App\Enums\AuctionBidEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionBid extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_winner' => 'boolean',
        'type' => AuctionBidEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
}
