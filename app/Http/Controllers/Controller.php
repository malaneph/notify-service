<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Notification Service API',
    description: 'API for dispatching SMS and email notifications to users and receiving delivery status webhooks from notification providers.'
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'Notification Service API server'
)]
abstract class Controller
{
    //
}
