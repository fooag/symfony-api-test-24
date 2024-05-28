<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'vermittler_user', schema: 'sec')]
class VermittlerUser
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    public int $id;

    #[ORM\Column(length: 200)]
    public string $email;

    #[ORM\Column(name: 'passwd', length: 60)]
    public string $password;

    #[ORM\Column]
    public bool $aktiv;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?DateTime $lastLogin;

    #[ORM\ManyToOne(targetEntity: Vermittler::class)]
    #[ORM\JoinColumn(name: 'vermittler_id', referencedColumnName: 'id', nullable: false)]
    public Vermittler $vermittler;
}
