<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentPicture extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function getPathAttribute($value): ?string
    {
        return $value ? env('APP_URL') . '/public' . $value : null;
    }
}
