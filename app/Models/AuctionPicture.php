<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuctionPicture extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

}
