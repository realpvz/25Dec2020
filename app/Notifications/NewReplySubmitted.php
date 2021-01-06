<?php

namespace App\Notifications;

use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewReplySubmitted extends Notification
{
    use Queueable;


    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

   
    public function toDatabase($notifiable)
    {
        return [
            'thread_title' => $this->thread->title,
            'url' => route('threads.show', [$this->thread]),
            'time' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
