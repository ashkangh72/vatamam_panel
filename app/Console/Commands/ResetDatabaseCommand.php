<?php

namespace App\Console\Commands;

use App\Jobs\AuctionWinnerJob;
use App\Models\Auction;
use App\Models\SafeBox;
use App\Models\SmsBox;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabaseCommand extends Command
{
    // The name and signature of the console command.
    protected $signature = 'reset:database';
    protected $description = 'Force truncate selected tables';

    public function handle()
    {
        $tables = [
            'wallet_histories',
            'wallet_checkout_transactions',
            // 'wallets',
            'wallet_checkouts',
            'vatamam_wallet_histories',
            'transactions',
            'tickets',
            'tickets_messages',
            // 'sms_boxes',
            'sms_logs',
            'safe_box_histories',
            // 'safe_boxes',
            'refunded_orders',
            'refunded_order_auction',
            'notifications',
            'notices',
            'discount_user',
            'discounts',
            'orders',
            'orders_feedbacks_files',
            'orders_feedbacks',
            'order_auction',
            'notification_settings',
        ];

        SmsBox::update(['balance' => 0]);
        SafeBox::update(['balance' => 0]);
        Wallet::update(['balance' => 0]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->info("Truncated: {$table}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->info("✅ Done truncating all listed tables.");
    }
}
