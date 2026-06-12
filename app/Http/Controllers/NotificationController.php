<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\SendRequest;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function send(SendRequest $request)
    {
        $data = $request->validated();

        NotificationService::send($data);
    }
}
