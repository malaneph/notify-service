<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SendNotificationRequest',
    title: 'Send Notification Request',
    description: 'Payload for dispatching a notification to one or more recipients.',
    required: ['priority', 'channel', 'recipients', 'message'],
    properties: [
        new OA\Property(
            property: 'priority',
            description: 'Determines the queue the notification job is dispatched to (high => critical, medium => default, low => bulk).',
            type: 'string',
            enum: ['high', 'medium', 'low'],
            example: 'high'
        ),
        new OA\Property(
            property: 'channel',
            description: 'The delivery channel used to send the notification.',
            type: 'string',
            enum: ['sms', 'email'],
            example: 'sms'
        ),
        new OA\Property(
            property: 'recipients',
            description: 'IDs of the users to notify.',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            example: [1, 2, 3]
        ),
        new OA\Property(
            property: 'message',
            description: 'The notification message body.',
            type: 'string',
            example: 'Your verification code is 123456'
        ),
        new OA\Property(
            property: 'idempotency_key',
            description: 'Optional client-provided key used to safely retry the request without sending duplicate notifications.',
            type: 'string',
            maxLength: 255,
            nullable: true,
            example: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890'
        ),
    ]
)]
class SendRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'priority' => 'required|in:high,medium,low',
            'channel' => 'required|in:sms,email',
            'recipients' => 'required|array',
            'message' => 'required|string',
            'idempotency_key' => 'sometimes|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
