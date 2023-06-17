<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphToMany};
use App\Enums\{AuctionConditionEnum, AuctionShippingMethodEnum, AuctionTimezoneEnum};

class Auction extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'condition' => AuctionConditionEnum::class,
        'timezone' => AuctionTimezoneEnum::class,
        'shipping_method' => AuctionShippingMethodEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(AuctionPicture::class);
    }

    /**
     * @return HasMany
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(AuctionSpecification::class);
    }

    /**
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @return HasMany
     */
    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }
}
