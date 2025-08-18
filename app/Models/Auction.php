<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, MorphMany, MorphToMany};
use App\Enums\{AuctionConditionEnum,
    AuctionShippingMethodEnum,
    AuctionStatusEnum,
    AuctionTimezoneEnum,
    AuctionBidTypeEnum,
    SafeBoxHistoryTypeEnum,
    WalletHistoryTypeEnum};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Auction extends Model
{
    use sluggable;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'condition' => AuctionConditionEnum::class,
        'timezone' => AuctionTimezoneEnum::class,
        'shipping_method' => AuctionShippingMethodEnum::class,
        'status' => AuctionStatusEnum::class,
    ];

    public static function generateSku(): string
    {
        $sku = rand(1000, 9999) . rand(1000, 9999);
        if (self::where('sku', $sku)->exists()) {
            return self::generateSku();
        }

        return $sku;
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
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

    /**
     * @return Model|null
     */
    public function highestBid(): Model|null
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }

    public function winnerBid(): Model|null
    {
        return $this->bids()->where('is_winner', true)->first();
    }

    /**
     * @return BelongsTo
     */
    public function originality(): BelongsTo
    {
        return $this->belongsTo(Originality::class);
    }

    /**
     * @return BelongsTo
     */
    public function historicalPeriod(): BelongsTo
    {
        return $this->belongsTo(HistoricalPeriod::class);
    }

    /**
     * @return HasMany
     */
    public function followers(): HasMany
    {
        return $this->hasMany(AuctionFollower::class);
    }

    /**
     * @return BelongsToMany
     */
    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_auction', 'auction_id', 'order_id');
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function winnerOrder()
    {
        $winnerBid = $this->winnerBid();

        if (!$winnerBid) return null;

        return $this->order()
            ->where('order_auction.auction_id', $winnerBid->auction_id)
            ->where('order_auction.user_id', $winnerBid->user_id)
            ->first();
    }

    public function isLoser(): bool
    {
        $loser = $this->bids()
            ->whereHas('loser')
            ->first();

        return $loser && $loser->user_id === auth()->id();
    }

    public function userBidRank($bids): string
    {
        $rank = 1;
        foreach ($bids as $bid) {
            if ($bid->user_id == auth()->id()) return (string)$rank;
            $rank++;
        }

        return '+10';
    }

    public function canBeQuickSold(): bool
    {
        if (!auth()->user()) return false;
        if ($this->is_ended) return false;
        if ($this->canceled_at) return false;

        if (auth()->user()->isVendor()) {
            if ($this->partner_quick_sale_price && $this->bids()->where('amount', '>=', $this->partner_quick_sale_price * 0.75)->exists()) {
                return false;
            }
        } else {
            if ($this->bids()->where('amount', '>=', $this->quick_sale_price * 0.75)->exists()) {
                return false;
            }
        }

        return true;
    }

    public function canBeBidded(): bool
    {
        if (auth()->check() || $this->canceled_at || $this->is_ended || $this->isBlacklisted() || $this->user_id !== auth()->id() || !$this->guaranteePricePaid()) return false;

        $userParticipatedAuctionsCount = auth()->user()->auctionBids()->groupBy('auction_id')->count();

        return $userParticipatedAuctionsCount < auth()->user()->vendor->minimum_auction_participation
            && !$this->bids()->whereIn('type', [AuctionBidTypeEnum::quick_sale_bid, AuctionBidTypeEnum::partner_quick_sale_bid])->exists();
    }

    public function guaranteePricePaid(): bool
    {
        if (!auth()->check()) return false;

        return $this->safeBoxHistory()
            ->whereHas('safeBox', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('type', SafeBoxHistoryTypeEnum::auction_guarantee)
            ->where('success', true)
            ->exists();
    }

    public function guaranteePrice(): float|int
    {
        return ceil($this->base_price * 0.1 / 1000) * 1000;
    }

    public function payUsingWallet($price): bool
    {
        $user = $this->user;
        $wallet = $user->wallet;
        $safeBox = $user->safeBox;

        if ($wallet->balance() >= $price) {
            DB::transaction(function () use ($wallet, $safeBox, $price) {
                $wallet->histories()->create([
                    'type' => WalletHistoryTypeEnum::withdraw,
                    'amount' => $price,
                    'description' => 'پرداخت هزینه تضمین برای مزایده ' . $this->title,
                    'success' => true,
                ]);
                $wallet->refreshBalance();

                $safeBox->histories()->create([
                    'type' => SafeBoxHistoryTypeEnum::auction_guarantee,
                    'amount' => $price,
                    'description' => 'پرداخت هزینه تضمین برای مزایده ' . $this->title,
                    'success' => true,
                    'historiable_type' => Auction::class,
                    'historiable_id' => $this->id,
                ]);
                $safeBox->refreshBalance();
            });

            return true;
        }

        return false;
    }

    public function isFavorite(): bool
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }

    public function isFollowing(): bool
    {
        return $this->followers()->where('user_id', auth()->id())->exists();
    }

    public function isBlacklisted(): bool
    {
        return $this->user->blacklist()->where('user_id', auth()->id())->exists();
    }

    public function safeBoxHistory(): MorphMany
    {
        return $this->morphMany(SafeBoxHistory::class, 'historiable');
    }

    public function getPictureAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }

    public function getVideoAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }

    public function getUrl(): string
    {
        if($this->type == 'product'){
            return env('WEBSITE_URL') . '/product/' . $this->slug;
        }else{
            return env('WEBSITE_URL') . '/auction/' . $this->slug;
        }
    }

    public function getType(): string
    {
        return match ($this->type) {
            'product' => 'محصول',
            'auction' => 'مزایده'
        };
    }

    public function scopeApproved($query)
    {
        $query->where('status', AuctionStatusEnum::approved);

        return $query;
    }

    public function scopeNotEnded($query)
    {
        if ($this->type == 'auction') {
            $query->where('is_ended', false);//->orWhere('end_at', '<', now()->addMonths(-1));
        } else {
            $query->where('is_ended', false);
        }

        return $query;
    }

    public function scopeAuction($query)
    {
        $query->where('type', 'auction');

        return $query;
    }
    
    public function scopeProduct($query)
    {
        $query->where('type', 'product');

        return $query;
    }

    public function scopeFilter($query, Request $request)
    {
        if ($title = $request->input('query.title')) {
            $query->where('title', 'like', "%$title%");
        }
        if ($sku = $request->input('query.sku')) {
            $query->where('sku', 'like', "%$sku%");
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'fullname':
                {
                    $query->join('users', 'orders.user_id', '=', 'users.id')
                        ->orderBy('users.first_name', $request->sort['sort'])
                        ->orderBy('users.last_name', $request->sort['sort'])
                        ->select('orders.*');
                    break;
                }
                case 'order_id':
                {
                    $query->orderBy('id', $request->sort['sort']);
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

    public function getTitle()
    {
        $title = ' - ' . ($this->type == 'auction' ? 'مزایده: ' : 'گالری آنلاین: ') . $this->title;
        return $title;
    }
}
