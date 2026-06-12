<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function sms(Request $request, string $referenceId)
    {
        NotificationService::handleReceipt($referenceId, $request->validated());

        return response()->noContent();
    }

    public function email(Request $request, string $referenceId)
    {
        NotificationService::handleReceipt($referenceId, $request->validated());

        return response()->noContent();
    }
}
