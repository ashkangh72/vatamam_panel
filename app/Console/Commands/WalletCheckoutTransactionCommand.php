<?php

namespace App\Console\Commands;

use App\Jobs\AuctionWinnerJob;
use App\Models\Auction;
use App\Models\WalletCheckoutTransaction;
use App\Services\JibitService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WalletCheckoutTransactionCommand extends Command
{
    // The name and signature of the console command.
    protected $signature = 'call:wallet_checkout_transaction';

    // Execute the console command.
    public function handle()
    {
        $transactions = WalletCheckoutTransaction::whereNotIn('status', [
            'TRANSFERRED',
            'FAILED',
            'TRANSFERRED_REVERTED',
            'FAILED_WRONG'
        ])->get();
        if(count($transactions)){
            $jibitService = new JibitService();
            
            foreach ($transactions as $transaction) {
                $jibitService->checkSettlement($transaction);
            }
        }
    }
}
