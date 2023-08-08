<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAuction extends Model
{
    protected $guarded = ['id'];
    protected $table = 'order_auction';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
