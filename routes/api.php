<?php
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use WendellAdriel\Idempotency\Http\Middleware\Idempotent;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/notifications')
    ->controller(NotificationController::class)
    ->group(function () {
        Route::post('send', 'send')->middleware(Idempotent::class);
    });

Route::prefix('/webhooks')
    ->controller(WebhookController::class)
    ->group(function () {
        Route::post('/sms', 'sms');
        Route::post('/email', 'email');
    });
