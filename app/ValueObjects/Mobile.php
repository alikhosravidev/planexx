<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Utilities\CustomValidator;
use InvalidArgumentException;

final readonly class Mobile
{
    public function __construct(public string $value)
    {
        if (!preg_match(CustomValidator::MOBILE_REGEX, $this->value)) {
            throw new InvalidArgumentException('Invalid mobile number. Must be in format 09xxxxxxxxx.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
