<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'bundesland', schema: 'public')]
class Bundesland
{
    #[ORM\Id]
    #[ORM\Column(length: 2, options: ['fixed' => true])]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public string $kuerzel;

    #[ORM\Column(type: Types::TEXT)]
    public string $name;

    public function __construct(string $kuerzel)
    {
        $this->kuerzel = $kuerzel;
    }
}
