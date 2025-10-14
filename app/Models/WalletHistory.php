<?php

namespace App\Models;

use App\Enums\WalletHistoryTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletHistory extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'type' => WalletHistoryTypeEnum::class
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function scopeSuccess($query)
    {
        return $query->where('success', true);
    }

    public function scopeFilter($query, $request)
    {
        if ($fullname = $request->input('query.fullname')) {
            $userIds = User::WhereRaw("name like '%{$fullname}%' ")->pluck('id');
            $walletIds = Wallet::whereIn('user_id', $userIds)->pluck('id');
            $query->whereIn('wallet_id', $walletIds);
        }

        if ($username = $request->input('query.username')) {
            $userIds = User::where('username', 'like', '%' . $username . '%')->pluck('id');
            $walletIds = Wallet::whereIn('user_id', $userIds)->pluck('id');
            $query->whereIn('wallet_id', $walletIds);
        }

        if ($id = $request->input('query.id')) {
            $walletIds = Wallet::where('user_id', $id)->pluck('id');
            $query->whereIn('wallet_id', $walletIds);
        }

        if ($phone = $request->input('query.phone')) {
            $userIds = User::where('phone', 'like', '%' . $phone . '%')->pluck('id');
            $walletIds = Wallet::whereIn('user_id', $userIds)->pluck('id');
            $query->whereIn('wallet_id', $walletIds);
        }

        if ($request->sort) {
            switch ($request->sort['field']) {
                case 'fullname': {
                        $query->orderBy('first_name', $request->sort['sort'])->orderBy('last_name', $request->sort['sort']);
                        break;
                    }
                case 'money': {
                        $query->join('wallets', 'users.id', '=', 'wallets.user_id')->select([
                            'users.id',
                            'name',
                            'username',
                            'phone',
                            'email',
                            'national_id',
                            'balance'
                        ])->orderBy('balance', $request->sort['sort']);
                        break;
                    }
                case 'box': {
                        $query->join('safe_boxes', 'users.id', '=', 'safe_boxes.user_id')->select([
                            'users.id',
                            'name',
                            'username',
                            'phone',
                            'email',
                            'national_id',
                            'balance'
                        ])->orderBy('balance', $request->sort['sort']);
                        break;
                    }
                default: {
                        if ($this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $request->sort['field'])) {
                            $query->orderBy($request->sort['field'], $request->sort['sort']);
                        }
                    }
            }
        }

        return $query;
    }
}
