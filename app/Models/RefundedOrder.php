<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RefundedOrder extends Model
{
    protected $guarded = null;

    public function auctions(): BelongsToMany
    {
        return $this->belongsToMany(Auction::class, 'refunded_order_auction')->withPivot(['quantity', 'reason', 'updated_at']);
    }

}
