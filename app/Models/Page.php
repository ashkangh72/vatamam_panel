<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Page extends Model
{
    use sluggable;

    protected $guarded = ['id'];

    public function sluggable():array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function link(): string
    {
        return env('WEBSITE_URL') . '/pages/' . $this->slug;
    }
}
