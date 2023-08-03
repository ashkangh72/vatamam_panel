<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasOne, HasMany, MorphMany};
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory, Notifiable, Authorizable;

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
            $query->WhereRaw("concat(first_name, ' ', last_name) like '%{$fullname}%' ");
        }

        if ($email = $request->input('query.email')) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        if ($username = $request->input('query.username')) {
            $query->where('username', 'like', '%' . $username . '%');
        }

        if ($level = $request->input('query.level')) {
            switch ($level) {
                case "admin":
                {
                    $query->where('level', 'admin');
                    break;
                }
                case "user":
                {
                    $query->where('level', 'user');
                    break;
                }
            }
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'fullname':
                {
                    $query->orderBy('first_name', $request->sort['sort'])->orderBy('last_name', $request->sort['sort']);
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
        return $this->hasMany(Auction::class,'user_id','id');
    }

    public function reports(): HasOne
    {
        return $this->hasOne(UserReport::class,'user_id','id');
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
}
