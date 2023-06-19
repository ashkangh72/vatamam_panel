<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    SoftDeletes
};

class Carrier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where(function ($q) {
            $q->where('carrige_forward', true)->orWhereHas('tariffs');
        });
    }

    public function getCityTarif($city_id, $weight)
    {
        $is_within_province = $this->province->cities()->find($city_id);

        if ($is_within_province) {
            $tariff = $this->tariffs()
                ->where('type', 'within_province')
                ->where('max_weight', '>=', $weight)
                ->orderBy('max_weight')
                ->first();

            if (!$tariff && $this->extra_cost) {
                $tariff = $this->tariffs()
                    ->where('type', 'within_province')
                    ->orderBy('max_weight')
                    ->first();
            }
        } else {
            $tariff = $this->tariffs()
                ->where('type', 'extra_province')
                ->where('max_weight', '>=', $weight)
                ->orderBy('max_weight')
                ->first();

            if (!$tariff && $this->extra_cost) {
                $tariff = $this->tariffs()
                    ->where('type', 'extra_province')
                    ->orderBy('max_weight')
                    ->first();
            }
        }

        return $tariff;
    }
}
