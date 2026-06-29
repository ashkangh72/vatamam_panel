<?php

namespace App\Models;

use App\Enums\ExpertCheckoutStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExpertCheckout extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ExpertCheckoutStatusEnum::class,
    ];

    public function expertCheckoutTransaction(): HasOne
    {
        return $this->hasOne(ExpertCheckoutTransaction::class);
    }
}
