<?php

declare(strict_types=1);

namespace App\ORM\Extension;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Kunde;
use Doctrine\ORM\QueryBuilder;

class SoftDeletableExtension implements QueryCollectionExtensionInterface
{

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void
    {
        if (Kunde::class === $resourceClass) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.geloescht = 0', $rootAlias));
        }
    }
}