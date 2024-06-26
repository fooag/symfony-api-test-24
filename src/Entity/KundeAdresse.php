<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Get;
use App\Repository\KundeAdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KundeAdresseRepository::class)]
#[ORM\Table('kunde_adresse', 'std')]
#[ApiResource(
    uriTemplate: '/kunden/{kundenId}/adressen/{addesseId}/details',
    operations: [new Get()],
    uriVariables: [
        'kundenId' => new Link(
            fromProperty: 'id',
            fromClass: Kunde::class
        ),
        'addesseId' => new Link(
            fromProperty: 'id',
            fromClass: Adresse::class
        )
    ],
)]
class KundeAdresse
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'kundeAdresses')]
    #[ORM\JoinColumn(name: 'kunde_id', nullable: false)]
    private ?Kunde $kunde = null;

    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'kundeAdresse', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'adresse_id', referencedColumnName: 'adresse_id', nullable: false)]
    private ?Adresse $adresse = null;

    #[ORM\Column]
    private ?bool $geschaeftlich = null;

    #[ORM\Column]
    private ?bool $rechnungsadresse = null;

    #[ORM\Column]
    private ?bool $geloescht = null;

    public function getKunde(): ?Kunde
    {
        return $this->kunde;
    }

    public function setKunde(?Kunde $kunde): void
    {
        $this->kunde = $kunde;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getGeschaeftlich(): ?bool
    {
        return $this->geschaeftlich;
    }

    public function setGeschaeftlich(?bool $geschaeftlich): void
    {
        $this->geschaeftlich = $geschaeftlich;
    }

    public function getRechnungsadresse(): ?bool
    {
        return $this->rechnungsadresse;
    }

    public function setRechnungsadresse(?bool $rechnungsadresse): void
    {
        $this->rechnungsadresse = $rechnungsadresse;
    }

    public function getGeloescht(): ?bool
    {
        return $this->geloescht;
    }

    public function setGeloescht(?bool $geloescht): void
    {
        $this->geloescht = $geloescht;
    }
}