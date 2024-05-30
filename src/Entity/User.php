<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Extension\ApiPlatform\State\UserDeleteProcessor;
use App\Extension\ApiPlatform\State\UserPasswordHasherProcessor;
use App\Security\UserPasswordValidator;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    security: 'is_granted("ROLE_VERMITTLER")',
)]
#[GetCollection(uriTemplate: '/user')]
#[Post(
    uriTemplate: '/user',
    validationContext: ['groups' => ['user:write']],
    processor: UserPasswordHasherProcessor::class,
)]
#[Get(uriTemplate: '/user/{id}')]
#[Put(
    uriTemplate: '/user/{id}',
    validationContext: ['groups' => ['user:write']],
    processor: UserPasswordHasherProcessor::class,
)]
#[Delete(
    uriTemplate: '/user/{id}',
    processor: UserDeleteProcessor::class,
)]
#[ORM\Entity]
#[ORM\Table(name: 'user', schema: 'sec')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;

    #[Assert\Email(groups: ['user:write'])]
    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:read', 'user:write'])]
    #[SerializedName('username')]
    #[ORM\Column(length: 200)]
    public string $email;

    // Der Regex ist für beispielsweise russische Eingaben nicht wirklich geeignet
    // Soll für diese Aufgabe jerdoch ausreichend sein
    #[Assert\Length(min: 8)]
    #[Assert\Regex(
        pattern: '/\d+/',
        message: 'Das Passwort muss mindestens eine Zahl beeinhalten.',
        groups: ['user:write'],
    )]
    #[Assert\Regex(
        pattern: '/[a-zäöüß]+/',
        message: 'Das Passwort muss mindestens einen Kleinbuchstaben beeinhalten.',
        groups: ['user:write'],
    )]
    #[Assert\Regex(
        pattern: '/[A-ZÄÖÜ]+/',
        message: 'Das Passwort muss mindestens einen Großbuchstaben beeinhalten.',
        groups: ['user:write'],
    )]
    #[Assert\Regex(
        pattern: '/\W+/',
        message: 'Das Passwort muss mindestens ein Sonderzeichen beeinhalten.',
        groups: ['user:write'],
    )]
    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:write'])]
    #[ORM\Column(name: 'passwd', length: 60)]
    public string $password;

    #[Groups(['user:read'])]
    #[ORM\Column]
    public int $aktiv;

    #[Groups(['user:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $lastLogin;

    #[ORM\ManyToOne(targetEntity: Kunde::class)]
    #[ORM\JoinColumn(name: 'kundenid', referencedColumnName: 'id', nullable: true)]
    public ?Kunde $kunde = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
