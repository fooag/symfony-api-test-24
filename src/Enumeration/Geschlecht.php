<?php

declare(strict_types=1);

namespace App\Enumeration;

enum Geschlecht: string
{
    case MAENNLICH = 'männlich';
    case WEIBLICH = 'weiblich';
    case DIVERS = 'divers';
}