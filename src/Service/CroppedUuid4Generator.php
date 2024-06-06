<?php

declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class CroppedUuid4Generator
{
    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 36;

    public function generate(int $length = 36): string
    {
        $this->validateLength($length);
        return mb_substr(
            string: Uuid::uuid4()->toString(),
            start: 0,
            length: $length,
        );
    }

    public function generateUppercase(int $length = 36): string
    {
        $this->validateLength($length);
        return mb_strtoupper(
            mb_substr(
                string: Uuid::uuid4()->toString(),
                start: 0,
                length: $length,
            ),
        );
    }

    private function validateLength(int $length): void
    {
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                sprintf(
                    "Expected parameter length to be in range [%d ; %d], got '%d'.",
                    self::MIN_LENGTH,
                    self::MAX_LENGTH,
                    $length,
                )
            );
        }
    }
}