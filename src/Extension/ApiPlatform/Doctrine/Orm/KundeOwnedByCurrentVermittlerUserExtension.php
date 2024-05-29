<?php

declare(strict_types=1);

namespace App\Extension\ApiPlatform\Doctrine\Orm;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Kunde;
use App\Entity\VermittlerUser;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class KundeOwnedByCurrentVermittlerUserExtension
    implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->applyConditions($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->applyConditions($queryBuilder, $resourceClass);
    }

    private function applyConditions(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if ($resourceClass !== Kunde::class) {
            return;
        }

        $user = $this->security->getUser();
        if ($user === null) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->join(
            join: VermittlerUser::class,
            alias: 'user',
            conditionType: Join::WITH,
            condition: sprintf('user = :current_user AND user.vermittler = %s.vermittler', $rootAlias)
        );
        $queryBuilder->setParameter('current_user', $user);
    }
}