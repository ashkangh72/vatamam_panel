<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;
    private string $title;
    private string $content;
    private string $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $title, string $content, string $url = null)
    {
        $this->user = $user;
        $this->title = $title;
        $this->content = $content;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return NotificationMail
     */
    public function build(): NotificationMail
    {
        return $this->view('mails.notification')
            ->subject($this->title)
            ->with([
                'title' => $this->title,
                'content' => $this->content,
                'url' => $this->url,
            ])
            ->to($this->user->email);
    }
}
