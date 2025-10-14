<?php

namespace App\Notifications;

use App\Enums\NotificationSettingKeyEnum;
use App\Mail\NotificationMail;
use App\Models\Auction;
use App\Models\Order;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AuctionRefoundCheckNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Order $order;
    private string $title;
    private string $message;
    private string $url;
    private string $type;

    public function __construct(Order $order, string $title, string $message, string $url, string $type)
    {
        $this->order = $order;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->type = $type;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        return notificationChannels($notifiable, $channels, NotificationSettingKeyEnum::auction_refound_check);
    }

    // public function toMail($notifiable)
    // {
    //     try {
    //         return (new NotificationMail($notifiable, $this->title, $this->message, $this->url))->to($notifiable->email);
    //     } catch (Exception $exception) {
    //         Log::error("AuctionEndNotification toMail failed for user_id {$notifiable->id} because: {$exception->getMessage()}.");
    //     }
    // }

    /**
     * @throws GuzzleException
     */
    public function toSms($notifiable)
    {
        sendSms($this->message, $notifiable, NotificationSettingKeyEnum::auction_refound_check->name);
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
            'order_id' => $this->order->id,
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
