<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFeedbackFile extends Model
{
    protected $table='orders_feedbacks_files';
    protected $fillable=['order_feedback_id','path'];


    public function getPathAttribute($value): ?string
    {
        return $value ? env('API_URL') . '/public' . $value : null;
    }
}
