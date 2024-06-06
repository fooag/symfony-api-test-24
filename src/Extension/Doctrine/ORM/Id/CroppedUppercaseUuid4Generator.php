<?php

declare(strict_types=1);

namespace App\Extension\Doctrine\ORM\Id;

use App\Service\CroppedUuid4Generator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use LogicException;

class CroppedUppercaseUuid4Generator extends AbstractIdGenerator
{
    private const LENGTH = 8;

    /**
     * Bei Id-Generatoren könnte man einen zusätzlichen Check auf Kollision einbauen. Im Rahmen dieser Testaufgabe
     * verzichte ich darauf.
     */
    public function generateId(EntityManagerInterface $em, $entity): string
    {
        if ($entity === null) {
            throw new LogicException('Expected parameter $entity to be not null.');
        }

        return (new CroppedUuid4Generator())->generateUppercase(self::LENGTH);
    }
}