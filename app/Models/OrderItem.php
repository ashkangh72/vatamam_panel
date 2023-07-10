<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\HasOne,
    Relations\BelongsTo
};

class OrderItem extends Model
{
    protected $guarded = ['id'];
    public $refundableDays = 10;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function get_price(): BelongsTo
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function refundedItem(): HasOne
    {
        return $this->hasOne(RefundedOrderItem::class);
    }

    public function realPrice()
    {
        return $this->real_price;
    }

    public function discountAmount()
    {
        return $this->quantity * ($this->real_price - $this->price);
    }
}
