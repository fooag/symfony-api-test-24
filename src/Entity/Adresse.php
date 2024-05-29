<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'adresse', schema: 'std')]
class Adresse
{
    #[ORM\Id]
    #[ORM\Column(name: 'adresse_id')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public int $id;

    #[ORM\Column(type: Types::TEXT)]
    public string $strasse;

    #[ORM\Column(length: 10)]
    public string $plz;

    #[ORM\Column(type: Types::TEXT)]
    public string $ort;

    #[ORM\ManyToOne(targetEntity: Bundesland::class)]
    #[ORM\JoinColumn(name: 'bundesland', referencedColumnName: 'kuerzel')]
    public Bundesland $bundesland;
}
