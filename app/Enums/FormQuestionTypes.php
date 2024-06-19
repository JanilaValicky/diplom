<?php

namespace App\Enums;

enum FormQuestionTypes: string
{
    case TEXT = 'text';
    case CHECKBOX = 'checkbox';
    case DATE = 'date';
    case FILE = 'file';
    case IMAGE = 'image';
    case HEADER = 'header';
    case NUMBER = 'number';
    case RADIO = 'radio';
    case SELECT = 'select';
    case TEXT_AREA = 'text_area';
    case PARAGRAPH = 'paragraph';

    public static function getAllValues(): array
    {
        return array_column(FormQuestionTypes::cases(), 'value');
    }
}
