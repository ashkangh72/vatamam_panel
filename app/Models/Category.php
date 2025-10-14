<?php

namespace App\Models;

use App\Enums\MenuTypeEnum;
use App\Traits\Taggable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};
use Illuminate\Support\Collection;

class Category extends Model
{
    use sluggable, Taggable, SoftDeletes;

    protected $guarded = ['id'];

    public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

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

    /**
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menus', 'menuable_id')->where('type', MenuTypeEnum::category);
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

    public function parents(): Collection
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents->reverse();
    }
}
