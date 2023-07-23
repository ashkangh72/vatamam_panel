<?php

namespace App\Http\Controllers\Back;

use App\Enums\WalletHistoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\{Wallet, WalletHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function show(Wallet $wallet)
    {
        $histories = $wallet->histories()->latest()->paginate(20);

        return view('back.wallets.show', compact('wallet', 'histories'));
    }

    public function create(Wallet $wallet)
    {
        return view('back.wallets.create', compact('wallet'));
    }

    public function store(Wallet $wallet, Request $request)
    {
        $data = $request->validate([
            'type'        => 'required|in:'. implode(',', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit'])) ,
            'amount'      => 'required|numeric|max:100000000',
            'description' => 'nullable'
        ]);

        $data['success'] = true;

        if ($data['type'] == WalletHistoryTypeEnum::admin_withdraw) {
            $request->validate([
                'amount' => 'numeric|max:' . $wallet->balance
            ]);
        }

        DB::transaction(function () use ($wallet, $data) {
            $wallet->histories()->create($data);

            if ($data['type'] == WalletHistoryTypeEnum::admin_withdraw) {
                $wallet->balance -= $data['amount'];
            } else {
                $wallet->balance += $data['amount'];
            }

            $wallet->save();
        });

        toastr()->success('تراکنش با موفقیت ایجاد شد');

        return response('success');
    }

    public function history(WalletHistory $history)
    {
        return view('back.wallets.history', compact('history'));
    }
}
