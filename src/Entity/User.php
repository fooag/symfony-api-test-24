<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Extension\ApiPlatform\State\UserDeleteProcessor;
use App\Extension\ApiPlatform\State\UserPostProcessor;
use App\Extension\ApiPlatform\State\UserPutProcessor;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new GetCollection(uriTemplate: '/user'),
        new Post(
            uriTemplate: '/user',
            validationContext: ['groups' => ['user:write']],
            processor: UserPostProcessor::class,
        ),
        new Get(uriTemplate: '/user/{id}'),
        new Put(
            uriTemplate: '/user/{id}',
            validationContext: ['groups' => ['user:write']],
            processor: UserPutProcessor::class,
        ),
        new Delete(
            uriTemplate: '/user/{id}',
            processor: UserDeleteProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    security: 'is_granted("ROLE_VERMITTLER")'
)]
#[ApiResource(
    shortName: 'Kunden',
    operations: [
        new GetCollection(
            uriTemplate: '/kunden/{id}/user',
            uriVariables: [
                'id' => new Link(
                    fromProperty: 'user',
                    fromClass: Kunde::class,
                ),
            ],
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    security: 'is_granted("ROLE_VERMITTLER")'
)]
#[ORM\Entity]
#[ORM\Table(name: 'user', schema: 'sec')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public int $id;

    #[Assert\Email(groups: ['user:write'])]
    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:read', 'user:write', 'kunde:read'])]
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

    #[Groups(['user:read', 'kunde:read'])]
    #[ORM\Column]
    public int $aktiv = 1;

    #[Groups(['user:read', 'kunde:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $lastLogin;

    #[Assert\NotBlank(groups: ['user:write'])]
    #[Groups(['user:write'])]
    #[ORM\ManyToOne(targetEntity: Kunde::class)]
    #[ORM\JoinColumn(name: 'kundenid', referencedColumnName: 'id', nullable: true)]
    public ?Kunde $kunde = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
