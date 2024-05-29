<?php

declare(strict_types=1);

namespace App\Extension\Doctrine\Doctrine\Orm;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Kunde;
use Doctrine\ORM\QueryBuilder;

class KundeNotGeloeschtExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
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

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(
            sprintf('%s.geloescht = :zero', $rootAlias)
        );
        $queryBuilder->setParameter('zero', 0);
    }
}