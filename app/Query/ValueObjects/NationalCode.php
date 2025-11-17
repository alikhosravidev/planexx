<?php

declare(strict_types=1);

namespace App\Query\ValueObjects;

use App\Utilities\NationalCodeValidator;
use InvalidArgumentException;

final readonly class NationalCode
{
    public function __construct(public string $value)
    {
        if (!NationalCodeValidator::isValid($this->value)) {
            throw new InvalidArgumentException('Invalid national code.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
