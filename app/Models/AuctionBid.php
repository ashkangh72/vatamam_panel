<?php

namespace App\Models;

use App\Enums\AuctionBidTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};
use Illuminate\Support\Facades\Request;

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

    public function scopeFilter($query, $request)
    {
        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'amount':
                {
                    $query->orderBy('amount', $request->sort['sort']);
                    break;
                }
                case 'created_at':
                {
                    $query->orderBy('created_at', $request->sort['sort']);
                    break;
                }
                default:
                {
                    if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
                        $query->orderBy($request->sort['field'], $request->sort['sort']);
                    }
                }
            }
        }

        return $query;
    }
}
