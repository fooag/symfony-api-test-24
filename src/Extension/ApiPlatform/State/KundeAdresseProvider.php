<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\KundeAdresse;
use Doctrine\ORM\EntityManagerInterface;

class KundeAdresseProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    // Eine kleine Krücke um den /kunden/{kundeId}/adressen/{adresseId}/details Endpunkt zu laufen zu kriegen.
    // Entweder lief der Endpunkt und alle anderen Endpunkte haben Fehler geworfen "Could not create IRI",
    // oder alle anderen Endpunkte liefen und der /kunden/{kundeId}/adressen/{adresseId}/details - Endpunkt warf
    // Doctrine-Fehler, da die Parameter "falsch" zur Query gemapped worden.
    // Erklärung dazu gerne später im PR oder Telefonat.
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        static $kundeAdresseFqns = KundeAdresse::class;
        $result = $this->em->createQuery(<<<DQL
            SELECT kundeAdresse
            FROM $kundeAdresseFqns kundeAdresse
            WHERE 
                kundeAdresse.kunde = :kundeId
                AND kundeAdresse.adresse = :adresseId
        DQL)
            ->setParameter('kundeId', $uriVariables['kundeId'])
            ->setParameter('adresseId', $uriVariables['adresseId'])
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
        return $result;
    }
}