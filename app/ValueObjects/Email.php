<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Utilities\CustomValidator;
use InvalidArgumentException;

final readonly class Email
{
    public function __construct(public string $value)
    {
        if (!preg_match(CustomValidator::EMAIL_REGEX, $this->value)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
