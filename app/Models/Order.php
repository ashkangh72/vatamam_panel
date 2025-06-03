<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\SafeBoxHistoryTypeEnum;
use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Support\Facades\DB;
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

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
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
        return $this->belongsToMany(Auction::class, 'order_auction')->withPivot(['id', 'quantity', 'status', 'price'])->withTrashed();
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(OrderFeedback::class, 'order_id', 'id');
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

    public function isLocked(): bool
    {
        return $this->status == OrderStatusEnum::locked;
    }

    public function isRefunded(): bool
    {
        return $this->refund()->exists();
    }

    public function refundPayment()
    {
        $userSafeBox = $this->user->safeBox;
        $userWallet = $this->user->wallet;

        $userAuctionGuaranteePaidAmounts = $userSafeBox->histories()
            ->where('type', SafeBoxHistoryTypeEnum::auction_guarantee)
            ->where('historiable_type', Auction::class)
            ->whereIn('historiable_id', $this->auctions->pluck('id'))
            ->success()
            ->sum('amount');
        $userOrderPaidAmounts = $userSafeBox->histories()
            ->where('type', SafeBoxHistoryTypeEnum::order)
            ->where('historiable_type', Order::class)
            ->where('historiable_id', $this->id)
            ->success()
            ->sum('amount');
        $totalUserPaidAmount = $userAuctionGuaranteePaidAmounts + $userOrderPaidAmounts;

        DB::transaction(function () use ($userSafeBox, $userWallet, $totalUserPaidAmount) {
            $userSafeBox->histories()->create([
                'user_id' => $this->seller->id,
                'type' => SafeBoxHistoryTypeEnum::checkout,
                'amount' => $totalUserPaidAmount,
                'balance' => $userSafeBox->balance - $totalUserPaidAmount,
                'description' => 'برداشت مبلغ تضمین مزایده ها و سفارش مرجوعی شماره ' . $this->id . ' و واریز به کیف پول',
                'historiable_type' => Order::class,
                'historiable_id' => $this->id,
                'success' => true,
            ]);
            $userSafeBox->refreshBalance();

            $userWallet->histories()->create([
                'type' => WalletHistoryTypeEnum::income,
                'amount' => $totalUserPaidAmount,
                'balance' => $userWallet->balance + $totalUserPaidAmount,
                'description' => 'واریز مبلغ مزایده ها و سفارش مرجوعی شماره ' . $this->id . ' از صندوق امانت',
                'historiable_type' => Order::class,
                'historiable_id' => $this->id,
                'success' => true,
            ]);
            $userWallet->refreshBalance();
        });
    }
}
