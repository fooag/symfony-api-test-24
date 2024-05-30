<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
    #[ORM\JoinColumn(name: 'adresse_id', referencedColumnName: 'adresse_id')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Adresse $adresse;

    #[Groups(['adresse:read', 'kunde:read'])]
    #[ORM\Column(options: ['default' => false])]
    public bool $geschaeftlich = false;

    #[Groups(['adresse:read', 'kunde:read'])]
    #[ORM\Column]
    public bool $rechnungsadresse;

    #[ORM\Column(options: ['default' => false])]
    public bool $geloescht = false;
}
