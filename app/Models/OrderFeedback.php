<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFeedback extends Model
{
    protected $table='orders_feedbacks';
    protected $fillable=['is_delivered','description'];

    public function files(): HasMany
    {
        return $this->hasMany(OrderFeedbackFile::class, 'order_feedback_id', 'id');
    }
}
