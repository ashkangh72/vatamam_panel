<?php

namespace App\Notifications;

use App\Enums\NotificationSettingKeyEnum;
use App\Mail\NotificationMail;
use App\Models\Discount;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DiscountNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Discount $discount;
    private string $title;
    private string $message;
    private string $url;
    private string $type;

    public function __construct(Discount $discount, string $title, string $message, string $url, string $type)
    {
        $this->discount = $discount;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->type = $type;

    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        return notificationChannels($notifiable, $channels, NotificationSettingKeyEnum::discounts);
    }

    // public function toMail($notifiable)
    // {
    //     try {
    //         return (new NotificationMail($notifiable, $this->title, $this->message, $this->url))->to($notifiable->email);
    //     } catch (Exception $exception) {
    //         Log::error("DiscountNotification toMail failed for user_id {$notifiable->id} because: {$exception->getMessage()}.");
    //     }
    // }

    /**
     * @throws GuzzleException
     */
    public function toSms($notifiable)
    {
        sendSms($this->message, $notifiable, NotificationSettingKeyEnum::discounts->name);
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
            'discount_id' => $this->discount->id
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
