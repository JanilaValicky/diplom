<?php

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Canceled = 'canceled';
    case Expired = 'expired';

    public static function getAllValues(): array
    {
        return array_column(SubscriptionStatusEnum::cases(), 'value');
    }
}
