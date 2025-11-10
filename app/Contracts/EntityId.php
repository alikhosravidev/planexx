<?php

declare(strict_types=1);

namespace App\Contracts;

abstract class EntityId
{
    public function __construct(
        public readonly int $value
    ) {
        $this->validate($value);
    }

    protected function validate(int $value): void
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException(static::class . ' expects a positive integer id. Given: ' . $value);
        }
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value && get_class($this) === get_class($other);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
