<?php

namespace App\Notifications;

use Exception;
use App\Models\Order;
use App\Mail\NotificationMail;
use App\Enums\NotificationSettingKeyEnum;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderUnSatisfiedNotification extends Notification implements ShouldQueue
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

        return notificationChannels($notifiable, $channels, NotificationSettingKeyEnum::order_unsatisfied);
    }

    /**
     * @throws GuzzleException
     */
    public function toSms($notifiable)
    {
        Log::error("to sms order paid");
        sendSms($this->message, $notifiable, NotificationSettingKeyEnum::order_unsatisfied->name);
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
            'order_id' => $this->order->id
        ];
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
