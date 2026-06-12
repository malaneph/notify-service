<?php

namespace App\Notifications;

use App\Http\Middleware\RedisRateLimit;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendSmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(readonly private string $subject, readonly private string $message)
    {
    }

    public function via($notifiable): array
    {
        return ['database', SmsChannel::class];
    }

    public function toSms($notifiable): SmsMessage
    {
        return (new SmsMessage)
            ->subject($this->subject)
            ->content($this->message);
    }

    public function toArray($notifiable): array
    {
        return ['message' => $this->message];
    }

    public function middleware(): array
    {
        return [new RedisRateLimit];
    }
}
