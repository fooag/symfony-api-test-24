<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Link;
use App\Repository\UserRepository;;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table('user', 'sec')]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/user'),
        new Post(
            uriTemplate: '/user',
            validationContext: ['groups' => ['Default', 'write']],
        ),
        new Get(uriTemplate: '/user/{id}'),
        new Put(
            uriTemplate: '/user/{id}',
            validationContext: ['groups' => ['Default', 'write']],
        ),
        new Delete(uriTemplate: '/user/{id}'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiResource(
    uriTemplate: '/kunden/{id}/user',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'kundeUser',
            fromClass: Kunde::class,

        )
    ],
    normalizationContext: ['groups' => ['read']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\Email(groups: ['write'])]
    #[Groups(['read'])]
    #[ORM\Column(length: 200, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'passwd')]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['write'])]
    //  8 Zeichen lang sein
    #[Assert\Length(min: 8, groups: ['write'])]
    // mind. eine Zahl
    #[Assert\Regex(pattern: '/\d+/', groups: ['write'])]
    // mind. ein Sonderzeichen enthalten
    #[Assert\Regex(pattern: '/\W+/', groups: ['write'])]
    // Groß/Kleinbuchstaben
    #[Assert\Regex(pattern: '/[A-Z]+/', groups: ['write'])]
    #[Assert\Regex(pattern: '/[a-z]+/', groups: ['write'])]
    #[Groups(['write'])]
    private ?string $plainPassword = null;

    #[ORM\OneToOne(inversedBy: 'kundeUser', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'kundenid', referencedColumnName: 'id', nullable: false)]
    private ?Kunde $kunde = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?bool $aktiv = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getKunde(): ?Kunde
    {
        return $this->kunde;
    }

    public function setKunde(?Kunde $kunde): void
    {
        $this->kunde = $kunde;
    }

    public function getAktiv(): ?bool
    {
        return $this->aktiv;
    }

    public function setAktiv(?bool $aktiv): void
    {
        $this->aktiv = $aktiv;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}