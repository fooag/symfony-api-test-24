<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enumeration\Geschlecht;
use App\Extension\Doctrine\ORM\Id\CroppedUppercaseUuid4Generator;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Kunden',
    normalizationContext: ['groups' => ['kunde:read']],
    denormalizationContext: ['groups' => ['kunde:write']],
    security: 'is_granted("ROLE_VERMITTLER")',
)]
#[GetCollection(uriTemplate: '/kunden')]
#[Post(uriTemplate: '/kunden', validationContext: ['groups' => ['kunde:write']])]
#[Get(uriTemplate: '/kunden/{id}')]
#[Put(uriTemplate: '/kunden/{id}', validationContext: ['groups' => ['kunde:write']])]
#[Delete(uriTemplate: '/kunden/{id}')]
#[ORM\Entity]
#[ORM\Table(name: 'tbl_kunden', schema: 'std')]
class Kunde
{
    #[Groups(['kunde:read'])]
    #[ORM\Id]
    #[ORM\Column(length: 36)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(CroppedUppercaseUuid4Generator::class)]
    public readonly string $id;

    #[Assert\NotBlank(groups: ['kunde:write'])]
    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column(name: 'name')]
    public string $nachname;

    #[Assert\NotBlank(groups: ['kunde:write'])]
    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column]
    public string $vorname;

    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $firma;

    #[Assert\NotBlank(groups: ['kunde:write'])]
    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public DateTime $geburtsdatum;

    #[ORM\Column]
    public int $geloescht;

    #[Assert\Choice(
        callback: [Geschlecht::class, 'cases'],
        groups: ['kunde:write'])
    ]
    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $geschlecht;

    #[Groups(['kunde:read', 'kunde:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $email;

    #[ORM\ManyToOne(targetEntity: Vermittler::class)]
    #[ORM\JoinColumn(name: 'vermittler_id', referencedColumnName: 'id', nullable: false)]
    public Vermittler $vermittler;
}
