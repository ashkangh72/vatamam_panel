<?php

namespace App\Notifications;

use App\Enums\NotificationSettingKeyEnum;
use App\Mail\NotificationMail;
use App\Models\Auction;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WinningAuctionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Auction $auction;
    private string $title;
    private string $message;
    private string $url;
    private string $type;

    public function __construct(Auction $auction, string $title, string $message, string $url, string $type)
    {
        $this->auction = $auction;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->type = $type;

    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        return notificationChannels($notifiable, $channels, NotificationSettingKeyEnum::winning_auction);
    }

    // public function toMail($notifiable)
    // {
    //     try {
    //         return (new NotificationMail($notifiable, $this->title, $this->message, $this->url))->to($notifiable->email);
    //     } catch (Exception $exception) {
    //         Log::error("WinningAuctionNotification toMail failed for user_id {$notifiable->id} because: {$exception->getMessage()}.");
    //     }
    // }

    /**
     * @throws GuzzleException
     */
    public function toSms($notifiable)
    {
        sendSms($this->message, $notifiable, NotificationSettingKeyEnum::winning_auction->name);
    }

    /**
     * @throws GuzzleException
     */
    public function toPush($notifiable)
    {
        sendPush([
            'title' => $this->title,
            'body' => $this->message,
            'url' => $this->url
        ], [$notifiable->push_token]);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => $this->type,
            'channels' => $this->via($notifiable),
            'title' => $this->title,
            'message' => $this->message,
            'auction_id' => $this->auction->id
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
