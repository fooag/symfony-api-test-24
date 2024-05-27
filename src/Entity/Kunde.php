<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\KundeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KundeRepository::class)]
#[ORM\Table('tbl_kunden', 'std')]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/kunden'),
        new Post(uriTemplate: '/kunden'),
        new Get(uriTemplate: '/kunden/{id}'),
        new Put(uriTemplate: '/kunden/{id}'),
        new Delete(uriTemplate: '/kunden/{id}'),
    ],
)]
class Kunde
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 36)]
    private string $id;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $vorname = null;

    #[ORM\Column(length: 255, nullable: false, name: 'name')]
    private ?string $nachname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $geburtsdatum = null;

    #[ORM\OneToMany(mappedBy: 'kunde', targetEntity: KundeAdresse::class, orphanRemoval: true)]
    private Collection $kundeAdresses;

    #[ORM\Column]
    private ?bool $geloescht = null;

    #[ORM\ManyToOne(inversedBy: 'kunden')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vermittler $vermittler = null;

    #[ORM\OneToOne(mappedBy: 'kunde', cascade: ['persist', 'remove'])]
    private ?User $kunde_user = null;

    public function __construct()
    {
        $this->kundeAdresses = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVorname(): ?string
    {
        return $this->vorname;
    }

    public function setVorname(?string $vorname): void
    {
        $this->vorname = $vorname;
    }

    public function getNachname(): ?string
    {
        return $this->nachname;
    }

    public function setNachname(?string $nachname): void
    {
        $this->nachname = $nachname;
    }

    public function getGeburtsdatum(): ?\DateTimeInterface
    {
        return $this->geburtsdatum;
    }

    public function setGeburtsdatum(?\DateTimeInterface $geburtsdatum): void
    {
        $this->geburtsdatum = $geburtsdatum;
    }

    public function getKundeAdresses(): Collection
    {
        return $this->kundeAdresses;
    }


    public function addKundeAdresse(KundeAdresse $kundeAdresse): void
    {
        if (!$this->kundeAdresses->contains($kundeAdresse)) {
            $this->kundeAdresses[] = $kundeAdresse;
        }
    }

    public function getGeloescht(): ?bool
    {
        return $this->geloescht;
    }

    public function setGeloescht(?bool $geloescht): void
    {
        $this->geloescht = $geloescht;
    }

    public function getVermittler(): ?Vermittler
    {
        return $this->vermittler;
    }

    public function setVermittler(?Vermittler $vermittler): void
    {
        $this->vermittler = $vermittler;
    }

    public function getKundeUser(): ?User
    {
        return $this->kunde_user;
    }

    public function setKundeUser(?User $kunde_user): void
    {
        $this->kunde_user = $kunde_user;
    }
}