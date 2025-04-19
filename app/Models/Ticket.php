<?php

namespace App\Models;

use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Ticket extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['status' => TicketStatusEnum::class];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->username) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('username', $request->username);
            });
        }

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
