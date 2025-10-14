<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionLoser extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function auctionBid(): BelongsTo
    {
        return $this->belongsTo(AuctionBid::class);
    }
}
