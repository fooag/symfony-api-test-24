<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enumeration\Bundesland;
use App\Extension\ApiPlatform\State\AdresseDeleteProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Adressen',
    normalizationContext: ['groups' => ['adresse:read']],
    denormalizationContext: ['groups' => ['adresse:write']],
    security: 'is_granted("ROLE_VERMITTLER")',
)]
#[GetCollection(uriTemplate: '/adressen')]
#[Post(uriTemplate: '/adressen', validationContext: ['groups' => ['adresse:write']])]
#[Get(uriTemplate: '/adressen/{id}')]
#[Put(uriTemplate: '/adressen/{id}', validationContext: ['groups' => ['adresse:write']])]
#[Delete(uriTemplate: '/adressen/{id}', processor: AdresseDeleteProcessor::class)]
#[ORM\Entity]
#[ORM\Table(name: 'adresse', schema: 'std')]
class Adresse
{
    #[ORM\Id]
    #[ORM\Column(name: 'adresse_id')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public int $id;

    #[Groups(['adresse:read'])]
    public function getAdresseId(): int
    {
        return $this->id;
    }

    #[Assert\NotBlank(groups: ['adresse:write'])]
    #[Groups(['adresse:read', 'adresse:write'])]
    #[ORM\Column(type: Types::TEXT)]
    public string $strasse;

    #[Assert\NotBlank(groups: ['adresse:write'])]
    #[Assert\Length(max: 10, groups: ['adresse:write'])]
    #[Groups(['adresse:read', 'adresse:write'])]
    #[ORM\Column(length: 10)]
    public string $plz;

    #[Assert\NotBlank(groups: ['adresse:write'])]
    #[Groups(['adresse:read', 'adresse:write'])]
    #[ORM\Column(type: Types::TEXT)]
    public string $ort;

    #[Assert\Choice(
        callback: [Bundesland::class, 'values'],
        groups: ['adresse:write'])
    ]
    #[Assert\Length(exactly: 2, groups: ['adresse:write'])]
    #[Assert\NotBlank(groups: ['adresse:write'])]
    #[Groups(['adresse:read', 'adresse:write'])]
    #[ORM\Column]
    public string $bundesland;

    #[ORM\OneToOne(mappedBy: 'adresse', targetEntity: KundeAdresse::class)]
    public ?KundeAdresse $kundeAdresse;
}
