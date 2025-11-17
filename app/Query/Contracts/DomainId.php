<?php

declare(strict_types=1);

namespace App\Query\Contracts;

use InvalidArgumentException;
use Stringable;

abstract class DomainId implements Stringable
{
    public function __construct(
        public readonly int|string $value
    ) {
        $this->validate($value);
    }

    protected function validate(int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s expects a positive integer id. Given: %d',
                    static::class,
                    $value
                )
            );
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
