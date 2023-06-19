<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo
};

class Tariff extends Model
{
    protected $guarded = ['id'];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    public function type(): string
    {
        if ($this->type == 'within_province') {
            return 'درون استانی';
        }

        return 'برون استانی';
    }
}
