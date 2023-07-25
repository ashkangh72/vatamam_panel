<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Comment extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    public function pictures(): HasMany
    {
        return $this->hasMany(CommentPicture::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getPictureAttribute($value): ?string
    {
        return $value ? env('APP_URL') . '/public' . $value : null;
    }

    public function scopeFilter($query, $request)
    {
        if ($request->status) {
            $query->where('status', $request->status);
        }

        switch ($request->ordering) {
            case 'oldest': {
                $query->oldest();
                break;
            }
            default: {
                $query->latest();
            }
        }

        return $query;
    }
}
