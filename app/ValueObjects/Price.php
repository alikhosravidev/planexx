<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Price
{
    public int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Price cannot be negative.');
        }

        $this->value = $value;
    }

    public function isLessThan(Price $other): bool
    {
        return $this->value < $other->value;
    }

    public function isGreaterThan(Price $other): bool
    {
        return $this->value > $other->value;
    }

    public function equals(Price $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
