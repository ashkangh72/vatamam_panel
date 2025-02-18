<?php

namespace App\Console\Commands;

use App\Jobs\AuctionWinnerJob;
use App\Models\Auction;
use Illuminate\Console\Command;

class CallWinnerJob extends Command
{
    // The name and signature of the console command.
    protected $signature = 'call:winner_job';
    // protected $signature = 'do:satisfied {name}';

    // Execute the console command.
    public function handle()
    {
        $auctions = Auction::where('type', 'auction')->get();
        foreach ($auctions as $auction) {
            dispatch(new AuctionWinnerJob($auction->id));
        }
    }
}
