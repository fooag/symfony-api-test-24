<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\KundeAdresse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AdressePostProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $kundeAdresse = new KundeAdresse();
        $kundeAdresse->kunde = $data->kunde;
        $kundeAdresse->adresse = $data;

        $this->em->persist($kundeAdresse);

        $data->kundeAdresse = $kundeAdresse;
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}