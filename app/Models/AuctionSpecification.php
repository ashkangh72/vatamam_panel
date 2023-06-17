<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionSpecification extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
}
