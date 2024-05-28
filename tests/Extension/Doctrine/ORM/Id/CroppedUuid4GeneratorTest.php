<?php

namespace App\Tests\Extension\Doctrine\ORM\Id;

use App\Extension\Doctrine\ORM\Id\CroppedUppercaseUuid4Generator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class CroppedUuid4GeneratorTest extends TestCase
{
    public function testGeneratedUuidIsLength8(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $generator = new CroppedUppercaseUuid4Generator();
        $id = $generator->generateId($em, new stdClass());

        self::assertEquals(
            expected: 8,
            actual: mb_strlen($id),
        );
    }

    public function testGeneratedUuidIsUppercaseCharactersAndNumbersOnly(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $generator = new CroppedUppercaseUuid4Generator(36);
        $id = $generator->generateId($em, new stdClass());

        self::assertMatchesRegularExpression(
            pattern: '/[A-F0-9]{8}/',
            string: $id,
        );
    }
}
