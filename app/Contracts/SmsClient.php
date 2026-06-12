<?php

namespace App\Contracts;

interface SmsClient
{
    public function send(string $to, string $message): void;
}
