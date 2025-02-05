<?php

namespace App\Jobs;

use App\Models\{User, Notice, Auction};
use App\Notifications\FavoriteNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NoticeAuctionJob implements ShouldQueue
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
        Log::error("in job notice handle");
        $categories[] = $this->auction->category->id;
        $categories = array_unique(array_merge($categories, $this->auction->category->parents()->toArray(), $this->auction->category->allChildCategories()));
        Log::error($categories);

        $noticesUserIds = Notice::whereRaw("noticeable_type = 'App\Models\Category' AND noticeable_id IN (" . implode(',', $categories) . ")");
        if ($this->auction->tags->count() > 0) {
            $noticesUserIds = $noticesUserIds->orWhereRaw("noticeable_type = 'App\Models\Tag' AND noticeable_id IN (" . implode(',', $this->auction->tags->pluck('id')->toArray()) . ")");
        }
        Log::error($noticesUserIds->count());

        if ($noticesUserIds->count() == 0) return;

        $users = User::whereIn('id', $noticesUserIds->pluck('user_id'))->get();

        $title = env('APP_NAME') . " - " . $this->auction->getType() . " جدید";
        $message = setNotificationMessage(
            'sms_on_notice_auction',
            'sms_text_on_notice_auction',
            ['auctionTitle' => $this->auction->title]
        );
        $url = env('WEBSITE_URL') . '/auction/' . $this->auction->slug;
        Log::error($message);

        if ($message)
            Notification::send($users, new FavoriteNotification($this->auction, $title, $message, $url, 'buy'));
    }
}
