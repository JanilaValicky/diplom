<?php

namespace App\Enums;

enum FormStatusEnum: string
{
    case Ongoing = 'ongoing';
    case Paused = 'paused';
    case Draft = 'draft';

    public static function getAllValues(): array
    {
        return array_column(FormStatusEnum::cases(), 'value');
    }
}
