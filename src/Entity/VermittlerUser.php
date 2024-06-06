<?php

namespace App\Entity;

use App\Enumeration\ApplicationUserRole;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'vermittler_user', schema: 'sec')]
class VermittlerUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public int $id;

    #[ORM\Column(length: 200)]
    public string $email;

    #[ORM\Column(name: 'passwd', length: 60)]
    public string $password;

    #[ORM\Column]
    public int $aktiv;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $lastLogin;

    #[ORM\ManyToOne(targetEntity: Vermittler::class)]
    #[ORM\JoinColumn(name: 'vermittler_id', referencedColumnName: 'id', nullable: false)]
    public Vermittler $vermittler;

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return [
            ApplicationUserRole::ROLE_VERMITTLER->value,
        ];
    }

    public function eraseCredentials(): void
    {
        // intentionally left empty -
        // there are no sensitive credentials stored in this class
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
