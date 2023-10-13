<?php

namespace App\Jobs;

use App\Models\Auction;
use App\Notifications\FollowedAuctionNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FollowedAuctionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $auctionId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $auctionId)
    {
        $this->auctionId = $auctionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $auction = Auction::find($this->auctionId);
        $followers = $auction->followers()->get();

        if (!$followers->count()) return;

        foreach ($followers as $follower) {
            $users[] = $follower->user;
        }
        $title = env('APP_NAME') . " - اتمام مزایده";
        $message = setNotificationMessage(
            'sms_on_followed_auction',
            'sms_text_on_followed_auction',
            ['auctionTitle' => $auction->title]
        );
        $url = env('WEBSITE_URL') . '/auction/' . $auction->slug;

        if ($message)
            Notification::send($users, new FollowedAuctionNotification($auction, $title, $message, $url, 'buy'));
    }
}
