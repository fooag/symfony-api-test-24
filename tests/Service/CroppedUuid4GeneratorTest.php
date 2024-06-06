<?php

namespace App\Tests\Service;

use App\Service\CroppedUuid4Generator;
use Generator;
use PHPUnit\Framework\TestCase;

class CroppedUuid4GeneratorTest extends TestCase
{

    /**
     * @dataProvider lengthDataProvider
     */
    public function testGenerateAcceptsLengthInRange8To36(int $length): void
    {
        $this->expectNotToPerformAssertions();
        (new CroppedUuid4Generator())->generate($length);
    }

    /**
     * @dataProvider lengthDataProvider
     */
    public function testGeneratedResultIsSameLengthAsParameter(int $length): void
    {
        self::assertEquals(
            expected: $length,
            actual: mb_strlen((new CroppedUuid4Generator())->generate($length)),
        );
    }

    /**
     * @dataProvider lengthDataProvider
     */
    public function testGenerateUppercaseAcceptsLengthInRange8To36(int $length): void
    {
        $this->expectNotToPerformAssertions();
        (new CroppedUuid4Generator())->generateUppercase($length);
    }

    /**
     * @dataProvider lengthDataProvider
     */
    public function testGeneratedUppercaseResultIsSameLengthAsParameter(int $length): void
    {
        self::assertEquals(
            expected: $length,
            actual: mb_strlen((new CroppedUuid4Generator())->generateUppercase($length)),
        );
    }

    /**
     * @dataProvider
     * @return Generator<int, array<int>>
     */
    public function lengthDataProvider(): Generator
    {
        for ($i = 8; $i <= 36; $i++) {
            yield $i => [$i];
        }
    }

    public function testGeneratedUppercaseResultIsActuallyUppercase(): void
    {
        // Ja ich weiß. Diese Gangsterregex soll für diese Aufgabe reichen.
        self::assertMatchesRegularExpression(
            pattern: '/^[A-F0-9-]{36}$/',
            string: (new CroppedUuid4Generator())->generateUppercase(),
        );
    }
}
