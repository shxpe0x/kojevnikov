<?php

declare(strict_types=1);

namespace App\Enums;

enum PostType: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case VIDEO = 'video';
    case LINK = 'link';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
