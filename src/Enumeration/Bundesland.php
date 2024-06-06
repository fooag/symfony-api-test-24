<?php

declare(strict_types=1);

namespace App\Enumeration;

enum Bundesland: string
{
    case BADEN_WUERTTEMBERG = 'BW';
    case BAYERN = 'BY';
    case BERLIN = 'BE';
    case BRANDENBURG = 'BB';
    case BREMEN = 'HB';
    case HAMBURG = 'HH';
    case HESSEN = 'HE';
    case MECKLENBURG_VORPOMMERN = 'MV';
    case NIEDERSACHSEN = 'NI';
    case NORDRHEIN_WESTFALEN = 'NW';
    case RHEINLAND_PFALZ = 'RP';
    case SAARLAND = 'SL';
    case SACHSEN = 'SN';
    case SACHSEN_ANHALT = 'ST';
    case SCHLESWIG_HOLSTEIN = 'SH';
    case THUERINGEN = 'TH';

    public static function values(): array
    {
        return array_map(static fn (Bundesland $bundesland): string => $bundesland->value, self::cases());
    }
}