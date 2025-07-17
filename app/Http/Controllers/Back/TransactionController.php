<?php

namespace App\Http\Controllers\Back;

use App\Enums\WalletHistoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\TransactionCollection;
use App\Models\Transaction;
use App\Models\WalletHistory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $this->authorize('transactions.payment.index');

        return view('back.transactions.index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('transactions.payment.index');


        $transactions = Transaction::whereHasMorph('transactionable', [WalletHistory::class], function ($q) {
                $q->whereNotIn('type', WalletHistoryTypeEnum::getValues(['admin_withdraw', 'admin_deposit']));
        })->filter($request);

        $transactions = datatable($request, $transactions);

        return new TransactionCollection($transactions);
    }

    public function show(Transaction $transaction)
    {
        return view('back.transactions.show', compact('transaction'))->render();
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('transactions.payment.delete');

        $transaction->delete();

        return response('success');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('transactions.payment.delete');

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        foreach ($request->ids as $id) {
            $transaction = Transaction::find($id);
            $this->destroy($transaction);
        }

        return response('success');
    }
}
