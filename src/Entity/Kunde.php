<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enumeration\Geschlecht;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    shortName: 'Kunden',
    security: 'is_granted("ROLE_VERMITTLER")'
)]
#[GetCollection(uriTemplate: '/kunden')]
#[ORM\Entity]
#[ORM\Table(name: 'tbl_kunden', schema: 'std')]
class Kunde
{
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public readonly string $id;

    #[ORM\Column(name: 'name')]
    public string $nachname;

    #[ORM\Column]
    public string $vorname;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $firma;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public DateTime $geburtsdatum;

    #[ORM\Column]
    public bool $geloescht;

    #[ORM\Column(type: Types::STRING, nullable: true, enumType: Geschlecht::class)]
    public ?Geschlecht $geschlecht;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $email;

    #[ORM\ManyToOne(targetEntity: Vermittler::class)]
    #[ORM\JoinColumn(name: 'vermittler_id', referencedColumnName: 'id', nullable: false)]
    public Vermittler $vermittler;
}
