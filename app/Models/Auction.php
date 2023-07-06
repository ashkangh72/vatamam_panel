<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphToMany};
use App\Enums\{AuctionConditionEnum, AuctionShippingMethodEnum, AuctionStatusEnum, AuctionTimezoneEnum};

class Auction extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'condition' => AuctionConditionEnum::class,
        'status' => AuctionStatusEnum::class,
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

    public function scopeFilter($query, $request)
    {
        if ($title = $request->input('query.title')) {
            $query->whereRaw("title like '%{$title}%' ");
        }
        if ($sku = $request->input('query.sku')) {
            $query->where('sku',$sku);
        }

//        if ($email = $request->input('query.email')) {
//            $query->where('email', 'like', '%' . $email . '%');
//        }
//
//        if ($username = $request->input('query.username')) {
//            $query->where('username', 'like', '%' . $username . '%');
//        }
//
//        if ($level = $request->input('query.level')) {
//            switch ($level) {
//                case "admin": {
//                    $query->where('level', 'admin');
//                    break;
//                }
//                case "user": {
//                    $query->where('level', 'user');
//                    break;
//                }
//            }
//        }

//        if ($request->sort) {
//            switch ($request->sort['field']) {
//                case 'fullname': {
//                    $query->orderBy('first_name', $request->sort['sort'])->orderBy('last_name', $request->sort['sort']);
//                    break;
//                }
//                default: {
//                    if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
//                        $query->orderBy($request->sort['field'], $request->sort['sort']);
//                    }
//                }
//            }
//        }

        return $query;
    }
    public function getImageUrlAttribute()
    {
        return $this->imageUrl();
    }

    public function imageUrl()
    {
        return $this->image ? asset($this->image) : asset('/back/app-assets/images/portrait/small/default.jpg');
    }
}
