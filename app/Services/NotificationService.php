<?php

namespace App\Services;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendEmailNotification;
use App\Notifications\SendSmsNotification;
use Illuminate\Http\Response;

class NotificationService
{
    public function __construct()
    {
    }

    public function send(mixed $data)
    {
        $queue = match ($data['priority']) {
            'high' => 'critical',
            'medium' => 'default',
            'low' => 'bulk'
        };

        foreach($data['recipients'] as $recipient) {
            $user = User::find($recipient);

            if (! $user) {
                continue;
            }

            $notification = match ($data['channel']) {
                'sms' => new SendSmsNotification($data['message'], $data['message']),
                'email' => new SendEmailNotification($data['message'], $data['message']),
            };

            $user->notify($notification->onQueue($queue));
        }
    }

    public function handleReceipt(string $referenceId, array $data)
    {
        $notification = Notification::find($referenceId);

        if (! $notification) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        $notification->update([
            'status' => $this->resolveStatus($data),
            'data' => [...$notification->data, 'error' => $data['error'] ?? null],
        ]);

        return response()->noContent();
    }

    public function resolveStatus(array $data): NotificationStatus
    {
        if (filled($data['error'] ?? null)) {
            return NotificationStatus::FAILED;
        }

        return NotificationStatus::tryFrom(strtolower($data['status'] ?? '')) ?? NotificationStatus::DELIVERED;
    }
}
