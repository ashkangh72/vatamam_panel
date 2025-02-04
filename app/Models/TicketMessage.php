<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    protected $guarded = ['id'];
    protected $table = 'tickets_messages';

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getFirstAttachmentAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }

    public function getSecondAttachmentAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
