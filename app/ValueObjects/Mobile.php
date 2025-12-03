<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\Utilities\CustomValidator;
use App\Utilities\StringUtility;
use InvalidArgumentException;

final readonly class Mobile
{
    public string $value;
    public function __construct(string $value)
    {
        $value = StringUtility::transformMobile($value);

        if (!preg_match(CustomValidator::MOBILE_REGEX, $value)) {
            throw new InvalidArgumentException('Invalid mobile number. Must be in format 09xxxxxxxxx.');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
