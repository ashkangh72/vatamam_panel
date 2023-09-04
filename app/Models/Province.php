<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($name = $request->input('query.name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($city_name = $request->input('query.city_name')) {
            $query->whereHas('cities', function ($q) use ($city_name) {
                $q->where('name', 'like', '%' . $city_name . '%');
            });
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'cities_count': {
                    $query->withCount('cities')->orderBy('cities_count', $request->sort['sort']);
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
}
