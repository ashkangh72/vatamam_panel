<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $guarded = ['id'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($name = $request->input('query.name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
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
