<?php

declare(strict_types=1);

namespace App\Query\ValueObjects;

final readonly class Slug
{
    public function __construct(public string $value)
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
