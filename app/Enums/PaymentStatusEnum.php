<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Succeeded = 'succeeded';
    case Pending = 'pending';
    case Failed = 'failed';
    case Canceled = 'canceled';
    case Refunded = 'refunded';
    case PartiallyRefunded = 'partially_refunded';
    case Disputed = 'disputed';

    public static function getAllValues(): array
    {
        return array_column(PaymentStatusEnum::cases(), 'value');
    }
}
