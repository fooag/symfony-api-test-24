<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'kunde_adresse', schema: 'std')]
class KundeAdresse
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Kunde::class)]
    #[ORM\JoinColumn(name: 'kunde_id', referencedColumnName: 'id')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Kunde $kunde;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Adresse::class)]
    #[ORM\JoinColumn(name: 'adresse_id', referencedColumnName: 'id')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Adresse $adresse;

    #[ORM\Column(options: ['default' => false])]
    public bool $geschaeftlich = false;

    #[ORM\Column]
    public bool $rechnungsadresse;

    #[ORM\Column(options: ['default' => false])]
    public bool $geloescht = false;
}
