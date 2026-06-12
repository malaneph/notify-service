<?php

namespace App\Notifications\Channels;

use App\Contracts\SmsClient;
use Illuminate\Notifications\Notification;
use RuntimeException;

class SmsChannel
{
    public function __construct(private SmsClient $client)
    {
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSms($notifiable);

        $to = $notifiable->routeNotificationFor('sms', $notification);

        if (! $to) {
            throw new RuntimeException(sprintf('No phone number found for notifiable [%s].', get_class($notifiable)));
        }

        $this->client->send($to, $message->content);
    }
}
