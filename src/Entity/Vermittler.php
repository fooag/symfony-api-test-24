<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'vermittler', schema: 'std')]
class Vermittler
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;

    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public string $nummer;

    #[ORM\Column]
    public string $vorname;

    #[ORM\Column]
    public string $nachname;

    #[ORM\Column]
    public string $firma;

    #[ORM\Column(options: ['default' => false])]
    public bool $geloescht = false;
}
