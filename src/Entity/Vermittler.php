<?php

namespace App\Entity;

use App\Repository\VermittlerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VermittlerRepository::class)]
#[ORM\Table('vermittler', 'std')]
class Vermittler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups(['read'])]
    #[ORM\Column(length: 36)]
    private ?string $nummer = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $vorname = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $nachname = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $firma = null;

    #[ORM\Column]
    private ?bool $geloescht = null;

    #[ORM\OneToMany(mappedBy: 'vermittler', targetEntity: Kunde::class)]
    private Collection $kunden;

    #[ORM\OneToMany(mappedBy: 'vermittler', targetEntity: VermittlerUser::class)]
    private Collection $vermittlerUsers;

    public function __construct()
    {
        $this->kunden = new ArrayCollection();
        $this->vermittlerUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNummer(): ?string
    {
        return $this->nummer;
    }

    public function setNummer(?string $nummer): void
    {
        $this->nummer = $nummer;
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

    public function getFirma(): ?string
    {
        return $this->firma;
    }

    public function setFirma(?string $firma): void
    {
        $this->firma = $firma;
    }

    public function getGeloescht(): ?bool
    {
        return $this->geloescht;
    }

    public function setGeloescht(?bool $geloescht): void
    {
        $this->geloescht = $geloescht;
    }

    public function getKunden(): Collection
    {
        return $this->kunden;
    }

    /**
     * @return Collection<int, VermittlerUser>
     */
    public function getVermittlerUsers(): Collection
    {
        return $this->vermittlerUsers;
    }
}