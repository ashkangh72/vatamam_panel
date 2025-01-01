<?php

namespace App\Models;

use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): string
    {
        switch ($this->transactionable_type) {
            case Order::class: {
                    $type = 'پرداخت سفارش';
                    break;
                }
            case WalletHistory::class: {
                    $type = 'شارژ کیف پول';
                    break;
                }
            case Auction::class: {
                    $type = 'گارانتی مزایده';
                    break;
                }
        }

        return $type ?? '--';
    }

    public function link(): string
    {
        switch ($this->transactionable_type) {

            case Order::class: {
                    $link = route('admin.orders.show', ['order' => $this->transactionable]);
                    break;
                }
            case WalletHistory::class: {
                    $link = route('admin.wallets.show', ['wallet' => $this->transactionable->wallet]);
                    break;
                }
            case Auction::class: {
                    $link = env('WEBSITE_URL') . '/auction/' . $this->transactionable->slug;
                    break;
                }
        }

        return $link ?? '--';
    }

    public function scopeFilter($query, $request)
    {
        if ($name = $request->input('query.name')) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->WhereRaw("name like '%{$name}%' ");
            });
        }

        if ($username = $request->input('query.username')) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->Where('username', 'like', "%$username%");
            });
        }

        if ($cardNumber = $request->input('query.cardNumber')) {
            $query->Where('cardNumber', 'like', "%$cardNumber%");
        }

        if ($transId = $request->input('query.transId')) {
            $query->Where('transId', 'like', "%$transId%");
        }

        if ($id = $request->input('query.id')) {
            $query->Where('id', $id);
        }

        $status = $request->input('query.status');

        if ($status !== null && in_array($status, ['0', '1'])) {
            $query->Where('status', $status);
        }

        if ($order_id = $request->input('query.order_id')) {
            $query->whereHasMorph('transactionable', [Order::class], function ($q) use ($order_id) {
                $q->where('id', $order_id);
            });
        }

        if ($auction_id = $request->input('query.auction_id')) {
            $query->whereHasMorph('transactionable', [Auction::class], function ($q) use ($auction_id) {
                $q->where('id', $auction_id);
            });
        }

        if ($transaction_type = $request->input('query.transaction_type')) {
            if ($transaction_type == 'by_admin' || $transaction_type == 'others') {
                $query->whereHasMorph('transactionable', [WalletHistory::class], function ($q) use ($transaction_type) {
                    if ($transaction_type == 'by_admin'){
                        $q->whereIn('type', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit']));
                    }else{
                        $q->whereNotIn('type', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit']));
                    }
                });
            }
        }

        if ($request->sort && $request->sort['field'] == 'name') {
            $query->join('users', 'transactions.user_id', '=', 'users.id')
                ->orderBy('users.name', $request->sort['sort'])
                ->select('transactions.*');
        } else if ($request->sort && $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
            $query->orderBy($request->sort['field'], $request->sort['sort']);
        }

        return $query;
    }
}
