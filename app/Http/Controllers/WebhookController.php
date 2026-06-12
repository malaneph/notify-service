<?php

namespace App\Http\Controllers;

use App\Enums\NotificationStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WebhookReceiptRequest',
    title: 'Webhook Receipt Request',
    description: 'Delivery status receipt sent by a notification provider for a previously sent notification.',
    properties: [
        new OA\Property(
            property: 'status',
            description: 'The delivery status reported by the provider.',
            type: 'string',
            enum: NotificationStatus::class,
            example: NotificationStatus::DELIVERED->value
        ),
        new OA\Property(
            property: 'error',
            description: 'Error message reported by the provider, if delivery failed.',
            type: 'string',
            nullable: true,
            example: null
        ),
    ]
)]
class WebhookController extends Controller
{
    #[OA\Post(
        path: '/api/webhooks/sms/{referenceId}',
        operationId: 'smsDeliveryWebhook',
        description: 'Receives delivery status updates for a previously sent SMS notification.',
        summary: 'SMS provider delivery webhook',
        tags: ['Webhooks'],
        parameters: [
            new OA\Parameter(
                name: 'referenceId',
                description: 'ID of the notification this receipt applies to.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/WebhookReceiptRequest')
        ),
        responses: [
            new OA\Response(response: 204, description: 'Receipt processed'),
            new OA\Response(response: 404, description: 'Notification not found'),
        ]
    )]
    public function sms(Request $request, string $referenceId)
    {
        NotificationService::handleReceipt($referenceId, $request->validated());

        return response()->noContent();
    }

    #[OA\Post(
        path: '/api/webhooks/email/{referenceId}',
        operationId: 'emailDeliveryWebhook',
        description: 'Receives delivery status updates for a previously sent email notification.',
        summary: 'Email provider delivery webhook',
        tags: ['Webhooks'],
        parameters: [
            new OA\Parameter(
                name: 'referenceId',
                description: 'ID of the notification this receipt applies to.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/WebhookReceiptRequest')
        ),
        responses: [
            new OA\Response(response: 204, description: 'Receipt processed'),
            new OA\Response(response: 404, description: 'Notification not found'),
        ]
    )]
    public function email(Request $request, string $referenceId)
    {
        NotificationService::handleReceipt($referenceId, $request->validated());

        return response()->noContent();
    }
}
