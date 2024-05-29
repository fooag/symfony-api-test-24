<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\VermittlerUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class KundePostProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /** @var VermittlerUser $user */
        $user = $this->security->getUser();
        $data->vermittler = $user->vermittler;
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}