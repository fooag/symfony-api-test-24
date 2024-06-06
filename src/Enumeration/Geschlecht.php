<?php

declare(strict_types=1);

namespace App\Enumeration;

enum Geschlecht: string
{
    case MAENNLICH = 'männlich';
    case WEIBLICH = 'weiblich';
    case DIVERS = 'divers';

    public static function values(): array
    {
        return array_map(static fn (Geschlecht $geschlecht): string => $geschlecht->value, self::cases());
    }
}