<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use App\Extension\ApiPlatform\State\KundeAdresseProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Kunden',
    operations: [
        new Get(
            uriTemplate: '/kunden/{kundeId}/adressen/{adresseId}/details',
            uriVariables: [
                'kundeId' => new Link(
                    fromProperty: 'adressen',
                    fromClass: Kunde::class,
                ),
                'adresseId' => new Link(
                    fromProperty: 'kundeAdresse',
                    fromClass: Adresse::class,
                ),
            ],
            provider: KundeAdresseProvider::class,
        ),
    ],
    normalizationContext: ['groups' => ['kunde_adresse:read']],
    denormalizationContext: ['groups' => ['kunde_adresse:write']],
    security: 'is_granted("ROLE_VERMITTLER")'
)]
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

    #[Groups(['kunde_adresse:read', 'adresse:read', 'kunde:read', 'adresse:write'])]
    #[ORM\Column(options: ['default' => false])]
    public bool $geschaeftlich = false;

    #[Groups(['kunde_adresse:read', 'adresse:read', 'kunde:read', 'adresse:write'])]
    #[ORM\Column]
    public bool $rechnungsadresse = false;

    #[ORM\Column(options: ['default' => false])]
    public bool $geloescht = false;
}
