<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\{Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasOne,
    Relations\MorphMany
};
use Illuminate\Http\Request;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(RefundedOrder::class);
    }

    public function auctions(): BelongsToMany
    {
        return $this->belongsToMany(Auction::class, 'order_auction')->withPivot(['id', 'quantity', 'status', 'price']);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(OrderFeedback::class, 'order_id', 'id');
    }

    public function getRefundedItems()
    {
        $itemIds = $this->items()->pluck('id')->toArray();

        return RefundedOrderItem::whereIn('order_item_id', $itemIds)->latest()->get();
    }

    public function isPaid(): bool
    {
        return $this->status == OrderStatusEnum::paid->value;
    }

    public function shippingStatusText(): string
    {
        if ($this->status != OrderStatusEnum::paid) {
            return 'منتظر پرداخت';
        }

        return match ($this->shipping_status) {
            'pending' => 'در حال بررسی',
            'shipping_request' => 'منتظر ارسال',
            'shipped' => 'ارسال شد',
            default => 'تعریف نشده',
        };
    }

    public function statusText(): string
    {
        return match ($this->status) {
            OrderStatusEnum::paid->value => 'پرداخت شده',
            OrderStatusEnum::pending->value => 'جدید',
            OrderStatusEnum::locked->value => 'پرداخت نشده',
            default => 'تعریف نشده',
        };
    }

    public function scopeFilter($query, Request $request)
    {
        if ($name = $request->input('query.name')) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->WhereRaw("name like '%{$name}%' ");
            })->orWhereHas('seller', function ($q) use ($name) {
                $q->WhereRaw("name like '%{$name}%' ");
            });
        }

        if ($username = $request->input('query.username')) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->Where('username', 'like', "%$username%");
            })->whereHas('seller', function ($q) use ($username) {
                $q->Where('username', 'like', "%$username%");
            });
        }

        $status = $request->input('query.status');
        if ($status && $status != 'all') {
            $query->Where('status', OrderStatusEnum::find($status)->value);
        }

        $shipping_status = $request->input('query.shipping_status');
        if ($shipping_status && $shipping_status != 'all') {
            $query->Where('shipping_status', $shipping_status);
        }

        if ($id = $request->input('query.id')) {
            $query->where('id', $id);
        }

        if ($request->sort) {
            match ($request->sort['field']) {
                'name' => $query->join('users', 'orders.user_id', '=', 'users.id')
                    ->orderBy('users.name', $request->sort['sort'])
                    ->select('orders.*'),
                'order_id' => $query->orderBy('id', $request->sort['sort']),
                default => $query->orderBy($request->sort['field'], $request->sort['sort']),
            };
        }

        return $query;
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class)->withTrashed();
    }

    public function scopePaid($query)
    {
        return $query->where('status', OrderStatusEnum::paid);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function requestToSend()
    {
        $this->shipping_status = OrderStatusEnum::send_request->value;
        $this->save();
    }

    public function isLocked(): bool
    {
        return $this->status == OrderStatusEnum::locked;
    }

    public function isRefunded(): bool
    {
        return $this->refund()->exists();
    }

}
