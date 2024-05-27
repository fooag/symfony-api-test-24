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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KundeRepository::class)]
#[ORM\Table('tbl_kunden', 'std')]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/kunden'),
        new Post(uriTemplate: '/kunden', validationContext: ['groups' => ['write']]),
        new Get(uriTemplate: '/kunden/{id}'),
        new Put(uriTemplate: '/kunden/{id}', validationContext: ['groups' => ['write']]),
        new Delete(uriTemplate: '/kunden/{id}'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Kunde
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 36)]
    #[Groups(['read'])]
    private string $id;

    #[Assert\NotBlank(groups: ['write'])]
    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $vorname = null;

    #[Assert\NotBlank(groups: ['write'])]
    #[Groups(['read', 'write'])]
    #[ORM\Column(name: 'name', length: 255, nullable: false)]
    private ?string $nachname = null;

    #[Assert\NotBlank(groups: ['write'])]
    #[Assert\Date(groups: ['write'])]
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $geburtsdatum = null;

    #[ORM\OneToMany(mappedBy: 'kunde', targetEntity: KundeAdresse::class, orphanRemoval: true)]
    private Collection $kundeAdresses;

    #[ORM\Column]
    private ?bool $geloescht = null;

    #[ORM\ManyToOne(inversedBy: 'kunden')]
    #[ORM\JoinColumn(name: 'vermittler_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['read'])]
    private ?Vermittler $vermittler = null;

    #[ORM\OneToOne(mappedBy: 'kunde', cascade: ['persist', 'remove'])]
    private ?User $kundeUser = null;

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
        return $this->kundeUser;
    }

    public function setKundeUser(?User $kundeUser): void
    {
        $this->kundeUser = $kundeUser;
    }
}