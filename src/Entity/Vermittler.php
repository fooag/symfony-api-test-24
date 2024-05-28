<?php

namespace App\Entity;

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
