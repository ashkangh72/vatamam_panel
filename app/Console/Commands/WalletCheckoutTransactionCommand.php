<?php

namespace App\Console\Commands;

use App\Models\ExpertCheckoutTransaction;
use App\Models\WalletCheckoutTransaction;
use App\Services\JibitService;
use Illuminate\Console\Command;

class WalletCheckoutTransactionCommand extends Command
{
    protected $signature = 'call:wallet_checkout_transaction';

    public function handle()
    {
        $finalStatuses = ['TRANSFERRED', 'FAILED', 'TRANSFERRED_REVERTED', 'FAILED_WRONG'];

        $walletTransactions = WalletCheckoutTransaction::whereNotIn('status', $finalStatuses)->get();
        $expertTransactions = ExpertCheckoutTransaction::whereNotIn('status', $finalStatuses)->get();

        if ($walletTransactions->isEmpty() && $expertTransactions->isEmpty()) {
            return;
        }

        $jibitService = new JibitService();

        foreach ($walletTransactions as $transaction) {
            $jibitService->checkSettlement($transaction);
        }

        foreach ($expertTransactions as $transaction) {
            $jibitService->checkExpertSettlement($transaction);
        }
    }
}
