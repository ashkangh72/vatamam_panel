<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class PushChannel
{
    /**
     * Send the given notification.
     *
     * @param $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification->toPush($notifiable);
    }
}
