<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\SendRequest;
use App\Services\NotificationService;
use OpenApi\Attributes as OA;

class NotificationController extends Controller
{
    #[OA\Post(
        path: '/api/notifications/send',
        operationId: 'sendNotification',
        description: 'Queues a notification for delivery to the given recipients over the requested channel.',
        summary: 'Send a notification',
        tags: ['Notifications'],
        parameters: [
            new OA\Parameter(
                name: 'Idempotency-Key',
                description: 'Unique key identifying this request. Replaying the same key returns the original response without re-sending the notification.',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/SendNotificationRequest')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Notification accepted and queued for delivery'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function send(SendRequest $request)
    {
        $data = $request->validated();

        NotificationService::send($data);
    }
}
