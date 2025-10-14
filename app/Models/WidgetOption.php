<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Support\Str;

class WidgetOption extends Model
{
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'widget_option_categories');
    }

    /**
     * @return BelongsToMany
     */
    public function auctions(): BelongsToMany
    {
        return $this->belongsToMany(Auction::class, 'widget_option_auctions');
    }


    public function hasCategory(): bool
    {
        return $this->value == 'on';
    }

    public function getValueAttribute($value): ?string
    {
        if (Str::startsWith($value, '/'))
            return $value ? env('API_URL') . '/public' . $value : null;
        else
            return $value;
    }

    public function setValueAttribute($value): void
    {
        if (Str::startsWith($value, env('API_URL')))
            $this->attributes['value'] = Str::replaceFirst(env('API_URL') . '/public', '', $value);
        else
            $this->attributes['value'] = $value;
    }
}
