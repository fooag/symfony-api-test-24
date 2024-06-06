<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

class AdresseDeleteProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    // Softdelete ist bei dieser Entität nicht möglich, da sie kein Flag besitzt. Im Rahmen der Aufgabe werde ich "nur"
    // die Verbindung zur Kundenadresse als gelöscht markieren
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data->kundeAdresse === null) {
            return;
        }
        $data->kundeAdresse->geloescht = true;
        $this->em->persist($data->kundeAdresse);
        $this->em->flush();
    }
}