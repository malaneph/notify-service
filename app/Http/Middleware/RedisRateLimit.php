<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisRateLimit
{
    public function handle($job, Closure $next)
    {
        Redis::throttle('send-sms')->allow(1)->every(1)->then(function () use ($job, $next) {
            $next($job);
        }, function () use ($job) {
            $job->release(60);
        });
    }
}
