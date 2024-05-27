<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AdresseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
#[ORM\Table('adresse', 'std')]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/adressen'),
        new Post(uriTemplate: '/adressen', validationContext: ['groups' => ['Default', 'write']]),
        new Get(uriTemplate: '/adressen/{id}'),
        new Put(uriTemplate: '/adressen/{id}', validationContext: ['groups' => ['Default', 'write']]),
        new Delete(uriTemplate: '/adressen/{id}'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiResource(
    uriTemplate: '/kunden/{id}/adressen',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'kundeAdresses',
            fromClass: Kunde::class,
        )
    ],
    normalizationContext: ['groups' => ['read']],
)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'adresse_id')]
    private int $id;

    #[Assert\NotBlank(groups: ['write'])]
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $strasse = null;

    #[Assert\NotBlank(groups: ['write'])]
    #[ORM\Column(length: 10, nullable: false)]
    private ?string $plz = null;

    #[Assert\NotBlank(groups: ['write'])]
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $ort = null;

    #[Assert\NotBlank(groups: ['write'])]
    #[ORM\Column(length: 2, nullable: false)]
    private ?string $bundesland = null;

    #[ORM\OneToOne(mappedBy: 'adresse', cascade: ['persist', 'remove'])]
    private ?KundeAdresse $kundeAdresse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStrasse(): ?string
    {
        return $this->strasse;
    }

    public function setStrasse(?string $strasse): void
    {
        $this->strasse = $strasse;
    }

    public function getPlz(): ?string
    {
        return $this->plz;
    }

    public function setPlz(?string $plz): void
    {
        $this->plz = $plz;
    }

    public function getOrt(): ?string
    {
        return $this->ort;
    }

    public function setOrt(?string $ort): void
    {
        $this->ort = $ort;
    }

    public function getBundesland(): ?string
    {
        return $this->bundesland;
    }

    public function setBundesland(?string $bundesland): void
    {
        $this->bundesland = $bundesland;
    }

    public function getKundeAdresse(): ?KundeAdresse
    {
        return $this->kundeAdresse;
    }

    public function setKundeAdresse(?KundeAdresse $kundeAdresse): void
    {
        $this->kundeAdresse = $kundeAdresse;
    }
}