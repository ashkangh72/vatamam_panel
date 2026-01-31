<?php

namespace App\Jobs;

use App\Models\{User, Notice, Auction};
use App\Notifications\FavoriteNotification;
use Exception;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AuctionBeforEndJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Auction $auction;

    /**
     * Create a new job instance.
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
        Log::error("in job notice");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::error("in job auction before end handle");
        $users = User::whereIn(
            'id',
            $this->auction->bids()->pluck('user_id')->unique()
        )->get();
        foreach ($users as $user) {
            $user->sendAuctionBeforeEndNotification($this->auction);
        }
    }
}
