<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Category extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function allChildCategories(): array
    {
        $categories = $this->children()->pluck('id')->toArray();
        $categories[] = $this->id;

        foreach ($this->children as $category) {
            $categories = array_merge($categories, $category->allChildCategories());
        }

        return $categories;
    }
}
