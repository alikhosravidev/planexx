<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class ColorHex
{
    public function __construct(public readonly string $value)
    {
        if (!preg_match('/^#?[0-9A-Fa-f]{6}$/', $this->value)) {
            throw new \InvalidArgumentException('Invalid color hex.');
        }
    }

    public function normalized(): string
    {
        return str_starts_with($this->value, '#') ? $this->value : '#' . $this->value;
    }

    public function __toString(): string
    {
        return $this->normalized();
    }
}
