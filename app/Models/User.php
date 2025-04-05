<?php

namespace App\Models;

use App\Enums\AuctionStatusEnum;
use App\Enums\WalletCheckoutStatusEnum;
use App\Notifications\{
    AuctionAcceptNotification,
    AuctionBeforeEndNotification,
    AuctionEndNotification,
    AuctionRefoundCheckNotification,
    AuctionRejectNotification,
    DiscountNotification,
    FavoriteNotification,
    FollowedAuctionNotification,
    OrderUnSatisfiedNotification,
    WinningAuctionNotification
};
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasOne, HasMany, MorphMany};
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory, Notifiable, Authorizable, SoftDeletes;

    /**
     * The attributes that are guarded.
     *
     * @var string[]
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function verificationCode(): HasOne
    {
        return $this->hasOne(VerificationCode::class);
    }

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }

    public function newsExcludedUser(): HasOne
    {
        return $this->hasOne(NewsExcludedUsers::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getWallet(): Model
    {
        return $this->wallet()->firstOrCreate(
            [],
            [
                'balance'   => 0,
                'is_active' => true
            ]
        );
    }

    public function isNewsExcluded(): bool
    {
        return $this->newsExcludedUser()->exists();
    }

    public function isVendor(): bool
    {
        return $this->vendor()->exists();
    }

    public function isAdmin()
    {
        return $this->level == 'admin' || $this->level == 'creator';
    }

    //scopes
    public function scopeFilter($query, $request)
    {
        if ($fullname = $request->input('query.fullname')) {
            $query->WhereRaw("name like '%{$fullname}%' ");
        }

        if ($email = $request->input('query.email')) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        if ($username = $request->input('query.username')) {
            $query->where('username', 'like', '%' . $username . '%');
        }

        if ($national_id = $request->input('query.national_id')) {
            $query->where('national_id', 'like', '%' . $national_id . '%');
        }

        if ($profile = $request->input('query.profile')) {
            if ($profile == 'completed')
                $query->whereNotNull('national_id');
            else if ($profile == 'not_completed')
                $query->whereNull('national_id');
        }

        if ($phone = $request->input('query.phone')) {
            $query->where('phone', 'like', '%' . $phone . '%');
        }

        if ($level = $request->input('query.level')) {
            switch ($level) {
                case "admin": {
                        $query->where('level', 'admin');
                        break;
                    }
                case "user": {
                        $query->where('level', 'user');
                        break;
                    }
            }
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'fullname': {
                        $query->orderBy('first_name', $request->sort['sort'])->orderBy('last_name', $request->sort['sort']);
                        break;
                    }
                case 'money': {
                        $query->join('wallets', 'users.id', '=', 'wallets.user_id')->select([
                            'users.id',
                            'name',
                            'username',
                            'phone',
                            'email',
                            'national_id',
                            'balance'
                        ])->orderBy('balance', $request->sort['sort']);
                        break;
                    }
                default: {
                        if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
                            $query->orderBy($request->sort['field'], $request->sort['sort']);
                        }
                    }
            }
        }

        return $query;
    }

    public function scopeCustomPaginate($query, $request)
    {
        $paginate = $request->paginate;
        $paginate = ($paginate && is_numeric($paginate)) ? $paginate : 10;

        if ($request->paginate == 'all') {
            $paginate = $query->count();
        }

        return $query->paginate($paginate);
    }

    public function scopeExcludeCreator($query)
    {
        return $query->where('level', '!=', 'creator');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function sendEmailVerificationNotification()
    {
        //        $this->notify(new VerifyEmailNotification());
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class, 'user_id', 'id');
    }

    public function reports(): HasOne
    {
        return $this->hasOne(UserReport::class, 'user_id', 'id');
    }

    public function auctionBids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }

    public function freeTimes(): HasMany
    {
        return $this->hasMany(FreeTime::class);
    }

    public function blacklist(): HasMany
    {
        return $this->hasMany(BlackList::class);
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(UserBankAccount::class);
    }

    public function safeBox(): HasOne
    {
        return $this->hasOne(SafeBox::class);
    }

    public function smsBox(): HasOne
    {
        return $this->hasOne(SmsBox::class);
    }

    public function walletCheckouts(): HasMany
    {
        return $this->hasMany(WalletCheckout::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class);
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(Viewer::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $role->intersect($this->roles)->count();
    }

    public function sendAuctionEndNotification(Auction $auction)
    {
        $title = env('APP_NAME') . " - اتمام مزایده";
        $message = setNotificationMessage(
            'sms_on_end_auction_to_seller',
            'sms_text_on_end_auction_to_seller',
            ['auctionTitle' => $auction->title]
        );
        $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;

        if (!$message) return;

        $this->notify(new AuctionEndNotification($auction, $title, $message, $url, 'sell'));
    }

    public function sendAuctionBeforeEndNotification(Auction $auction)
    {
        $title = env('APP_NAME') . " - اتمام مزایده";
        $message = setNotificationMessage(
            'sms_on_auction_before_end',
            'sms_text_on_auction_before_end',
            ['auctionTitle' => $auction->title]
        );
        $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;

        if (!$message) return;

        $this->notify(
            (new AuctionBeforeEndNotification($auction, $title, $message, $url, 'sell'))
                ->delay(Carbon::now()->addMinutes(30))
        );
    }

    public function sendWinningAuctionNotification(Auction $auction)
    {
        $title = env('APP_NAME') . " - برنده شدید!";
        $message = setNotificationMessage(
            'sms_on_win_auction_to_buyer',
            'sms_text_on_win_auction_to_buyer',
            ['auctionTitle' => $auction->title]
        );
        $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;

        if (!$message) return;

        $this->notify(new WinningAuctionNotification($auction, $title, $message, $url, 'buy'));
    }

    public function sendAuctionAcceptNotification(Auction $auction)
    {
        if ($auction->type == 'auction') {
            $title = env('APP_NAME') . " - تایید مزایده";
            $message = setNotificationMessage(
                'sms_on_accept_auction_to_seller',
                'sms_text_on_accept_auction_to_seller',
                ['auctionTitle' => $auction->title]
            );

            $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;
        } else {
            $title = env('APP_NAME') . " - تایید محصول";
            $message = setNotificationMessage(
                'sms_on_accept_product_to_seller',
                'sms_text_on_accept_product_to_seller',
                ['productTitle' => $auction->title]
            );

            $url = env('WEBSITE_URL') . '/product/' . $auction->slug;
        }


        if (!$message) return;

        $this->notify(new AuctionAcceptNotification($auction, $title, $message, $url, 'sell'));
    }

    public function sendAuctionRejectNotification(Auction $auction)
    {
        if ($auction->type == 'auction') {
            $title = env('APP_NAME') . " - رد مزایده";
            $message = setNotificationMessage(
                'sms_on_reject_auction_to_seller',
                'sms_text_on_reject_auction_to_seller',
                ['auctionTitle' => $auction->title, 'reason' => $auction->reject_reason]
            );

            $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;
        } else {
            $title = env('APP_NAME') . " - رد محصول";
            $message = setNotificationMessage(
                'sms_on_reject_product_to_seller',
                'sms_text_on_reject_product_to_seller',
                ['productTitle' => $auction->title, 'reason' => $auction->reject_reason]
            );

            $url = env('WEBSITE_URL') . '/product/' . $auction->slug;
        }
        if (!$message) return;

        $this->notify(new AuctionRejectNotification($auction, $title, $message, $url, 'sell'));
    }

    public function sendRefoundCheckNotification(Order $order)
    {
        $title = env('APP_NAME') . " - بررسی اعلام نارضایتی";
        $message = setNotificationMessage(
            'sms_on_accept_unsatisfied_product_to_buyer',
            'sms_text_on_accept_unsatisfied_product_to_buyer',
            []
        );
        $url = env('WEBSITE_URL') . '/profile/buying/buying-basket/' . $order->id;

        if (!$message) return;

        $this->notify(new AuctionRefoundCheckNotification($order, $title, $message, $url, 'buy'));
    }

    public function sendOrderUnSatisfiedNotification(Order $order)
    {
        $title = env('APP_NAME') . " - اعلام نارضایتی مشتری";
        $message = setNotificationMessage(
            'sms_on_unsatisfied_product_to_seller',
            'sms_text_on_unsatisfied_product_to_seller',
            []
        );

        $url = env('WEBSITE_URL') . '/profile/selling/selling-basket/' . $order->id;

        if (!$message) return;

        $this->notify(new OrderUnSatisfiedNotification($order, $title, $message, $url, 'sell'));
    }

    function panelNotifies($type)
    {
        if ($type == 'new_auctions_products') {
            return Auction::where('status', AuctionStatusEnum::pending_approval)->count();
        }

        if ($type == 'new_auctions') {
            return Auction::where('type', 'auction')->where('status', AuctionStatusEnum::pending_approval)->count();
        }

        if ($type == 'new_products') {
            return Auction::where('type', 'product')->where('status', AuctionStatusEnum::pending_approval)->count();
        }

        if ($type == 'checkouts') {
            return WalletCheckout::where('status', WalletCheckoutStatusEnum::pending_approval)->count();
        }

        if ($type == 'transactions') {
            $lastLogin = Viewer::where('user_id', $this->id)->where('path', 'like', '%' . '/transactions/api/index' . '%')->orderBy('created_at', 'desc')->first();
            return is_null($lastLogin) ? Transaction::count() : Transaction::where('created_at', '>', $lastLogin->created_at)->count();
        }

        return 0;
    }

}
