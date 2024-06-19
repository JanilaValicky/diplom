<?php

namespace App\Enums;

enum HtmlWritingDirection: string
{
    case RightToLeft = 'rtl';
    case LeftToRight = 'ltr';

    public static function getAllValues(): array
    {
        return array_column(HtmlWritingDirection::cases(), 'value');
    }
}
