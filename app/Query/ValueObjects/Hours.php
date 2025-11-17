<?php

declare(strict_types=1);

namespace App\Query\ValueObjects;

final readonly class Hours
{
    public function __construct(public int $value)
    {
        if ($this->value < 0) {
            throw new \InvalidArgumentException('Invalid hours.');
        }
    }

    public function asString(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return $this->asString();
    }
}
