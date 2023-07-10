<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\{Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne,
    Relations\MorphMany
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class)->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withTrashed();
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(RefundedOrder::class);
    }

    public function hasPhysicalItem(): bool
    {
        return $this->products()->where('type', 'physical')->exists();
    }

    public function auctions(): BelongsToMany
    {
        return $this->belongsToMany(Auction::class, 'order_auction')->withPivot(['price', 'id', 'discount_price', 'discount_amount', 'title', 'quantity', 'status']);
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

    public function getShipStatusAttribute(): string
    {
        return $this->shippingStatusText();
    }

    public function shippingStatusText(): string
    {
        if ($this->hasPhysicalItem()) {

            if ($this->status != 'paid') {
                return 'منتظر پرداخت';
            }

            $text = '';

            switch ($this->shipping_status) {
                case 'pending':
                {
                    $text = 'در حال بررسی';
                    break;
                }
                case 'wating':
                {
                    $text = 'منتظر ارسال';
                    break;
                }
                case 'sent':
                {
                    $text = 'ارسال شد';
                    break;
                }
                case 'canceled':
                {
                    $text = 'ارسال لغو شد';
                    break;
                }
            }
            return $text;
        }

        return 'سفارش شما شامل محصول فیزیکی نمی باشد';
    }

    public function statusText(): string
    {
        switch ($this->status) {
            case "paid":
            {
                return 'پرداخت شده';
            }

            case "unpaid":
            {
                return 'پرداخت نشده';
            }

            case "canceled":
            {
                return 'لغو شده';
            }

            default:
                return 'تعریف نشده';
        }
    }

    public function scopeFilter($query, Request $request)
    {

        if ($fullname = $request->input('query.fullname')) {
            $query->whereHas('user', function ($q) use ($fullname) {
                $q->WhereRaw("concat(first_name, ' ', last_name) like '%{$fullname}%' ");
            });
        }

        if ($username = $request->input('query.username')) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->Where('username', 'like', "%$username%");
            });
        }

        $status = $request->input('query.status');

        if ($status && $status != 'all') {
            $query->Where('status', $status);
        }

        $shipping_status = $request->input('query.shipping_status');

        if ($shipping_status && $shipping_status != 'all') {
            $query->Where('shipping_status', $shipping_status);
        }

        if ($id = $request->input('query.id')) {
            $query->where('id', $id);
        }

        $warehouse_id = $request->input('query.warehouse_id');
        if ($warehouse_id && $warehouse_id != 'all') {
            $query->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('prices', 'order_items.price_id', '=', 'prices.id')
                ->where('prices.warehouse_id', $warehouse_id)
                ->groupBy('orders.id')
                ->select('orders.*');
        }

        if ($request->sort) {

            switch ($request->sort['field']) {
                case 'fullname':
                {
                    $query->join('users', 'orders.user_id', '=', 'users.id')
                        ->orderBy('users.first_name', $request->sort['sort'])
                        ->orderBy('users.last_name', $request->sort['sort'])
                        ->select('orders.*');
                    break;
                }
                case 'order_id':
                {
                    $query->orderBy('id', $request->sort['sort']);
                    break;
                }
                default:
                {
                    if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
                        $query->orderBy($request->sort['field'], $request->sort['sort']);
                    }
                }
            }
        }

        return $query;
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class)->withTrashed();
    }

    public function gatewayRelation(): BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function totalDiscount()
    {
        $items_discount = 0;

        foreach ($this->items as $item) {
            $items_discount += $item->discountAmount();
        }

        return $this->discount_amount;
    }

    public function scopeNotCompleted($query)
    {
        return $query->where('status', 'paid')->whereNotIn('shipping_status', ['sent', 'canceled']);
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', [OrderStatusEnum::paid, OrderStatusEnum::locked, OrderStatusEnum::sending, OrderStatusEnum::send_request]);
    }

    public function scopeNotPaid($query)
    {
        return $query->where('status', '!=', 'paid');
    }

    public function scopeNotCanceled($query)
    {
        return $query->where('status', '!=', 'canceled');
    }

    public function hasPhysicalProduct(): bool
    {
        foreach ($this->products as $product) {
            if ($product->isPhysical()) {
                return true;
            }
        }

        return false;
    }

    public function payUsingWallet($price): bool
    {
        $user = $this->user;
        $wallet = $user->wallet;

        if ($wallet->balance() >= $price) {
            DB::transaction(function () use ($wallet, $price) {
                $this->update([
                    'status' => OrderStatusEnum::paid
                ]);

                $wallet->histories()->create([
                    'type' => WalletHistoryTypeEnum::withdraw,
                    'amount' => $price,
                    'description' => 'ثبت سفارش شماره ' . $this->id . ' به مبلغ ' . $price . ' تومان',
                    'success' => true,
                ]);

                $wallet->refereshBalance();
            });

            return true;
        }

        return false;
    }

    public function walletHistory(): HasOne
    {
        return $this->hasOne(WalletHistory::class)->where('status', 'success');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class)->withTrashed();
    }

    public function seller()
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
