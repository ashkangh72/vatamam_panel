<?php

namespace App\Http\Controllers\Back;

use App\Enums\WalletHistoryTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\{Wallet, WalletHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function show(Wallet $wallet)
    {
        $this->authorize('users.wallets.show');

        $histories = $wallet->histories()->latest()->paginate(20);

        return view('back.wallets.show', compact('wallet', 'histories'));
    }

    public function create(Wallet $wallet)
    {
        $this->authorize('users.wallets.create');

        return view('back.wallets.create', compact('wallet'));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Wallet $wallet, Request $request)
    {
        $this->authorize('users.wallets.create');

        $data = $request->validate([
            'type'        => 'required|in:'. implode(',', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit'])) ,
            'amount'      => 'required|numeric|max:100000000',
            'description' => 'nullable'
        ]);

        $data['success'] = true;

        if ($data['type'] == WalletHistoryTypeEnum::admin_withdraw) {
            $request->validate([
                'amount' => 'numeric|max:' . $wallet->balance()
            ]);

            $data['balance'] = $wallet->balance() - $data['amount'];
        } else {
            $data['balance'] = $wallet->balance() + $data['amount'];
        }

        DB::transaction(function () use ($wallet, $data) {
            $wallet->histories()->create($data);

            $wallet->refreshBalance();
        });

        toastr()->success('تراکنش با موفقیت ایجاد شد');

        return response('success');
    }

    public function history(WalletHistory $history)
    {
        $this->authorize('users.wallets.history.show');

        return view('back.wallets.history', compact('history'));
    }
}
