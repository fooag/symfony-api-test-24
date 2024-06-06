<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

class KundeDeleteProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        foreach ($data->adressen as $adresse) {
            $adresse->geloescht = true;
            $this->em->persist($adresse);
        }

        $data->user->aktiv = 0;
        $this->em->persist($data->user);

        $data->geloescht = 1;
        $this->em->persist($data);

        $this->em->flush();
    }
}