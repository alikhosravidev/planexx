<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class Slug
{
    public function __construct(public readonly string $value)
    {
        if ($this->value === '' || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $this->value)) {
            throw new \InvalidArgumentException('Invalid slug.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
