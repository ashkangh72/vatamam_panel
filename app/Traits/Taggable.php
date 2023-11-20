<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getGetTagsAttribute(): string
    {
        return implode(',', $this->tags()->pluck('name')->toArray());
    }

    public static function bootTaggable(): void
    {
        static::observe(app(TaggableObserver::class));
    }
}
