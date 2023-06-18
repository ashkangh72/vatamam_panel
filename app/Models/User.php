<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany};
use Illuminate\Notifications\Notifiable;

use Illuminate\Auth\Authenticatable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory, Notifiable;

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

    public function isNewsExcluded(): bool
    {
        return $this->newsExcludedUser()->exists();
    }

    public function isVendor(): bool
    {
        return $this->vendor()->exists();
    }
}
