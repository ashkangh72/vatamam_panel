<?php

namespace App\Http\Controllers\Back;

use App\Enums\WalletHistoryTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\{Transaction, VatamamWalletHistory, Wallet, WalletHistory};
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

    public function showVatamam()
    {
        $this->authorize('users.wallets.show');

        $histories = VatamamWalletHistory::latest()->paginate(20);
        
        $total = VatamamWalletHistory::sum('amount');

        return view('back.wallets.show_vatamam', compact('total', 'histories'));
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
            'type'        => 'required|in:' . implode(',', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit'])),
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        $data['success'] = true;

        $balance = $wallet->balance();
        $data['balance'] = match ((int)$data['type']) {
            WalletHistoryTypeEnum::admin_withdraw->value => $balance - $data['amount'],
            WalletHistoryTypeEnum::admin_deposit->value => $balance + $data['amount']
        };

        DB::transaction(function () use ($wallet, $data) {
            $history = $wallet->histories()->create($data);

            Transaction::create([
                'status' => true,
                'amount' => $data['amount'],
                'description' => WalletHistoryTypeEnum::admin_deposit->value ==  (int)$data['type'] ? 'تراکنش ایجاد شده توسط ادمین - اضافه کردن به کیف پول' : 'تراکنش ایجاد شده توسط ادمین - کم کردن از کیف پول',
                'transId' => rand(),
                'user_id' => auth()->user()->id,
                'transactionable_type' => WalletHistory::class,
                'transactionable_id' => $history->id,
                'gateway' => null,
                'cardNumber' => null,
                'traceNumber' => null,
            ]);

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
