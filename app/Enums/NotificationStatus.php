<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case PENDING = 'pending';
    case DELIVERED = 'delivered';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
