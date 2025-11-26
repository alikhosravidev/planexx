<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\ValueObjects;

use App\Core\Organization\Services\Auth\DTOs\AuthConfig;
use App\Core\Organization\Services\Auth\Enums\IdentifierType;
use App\Core\Organization\Services\Auth\Exceptions\IdentifierException;
use App\Utilities\CustomValidator;
use App\Utilities\StringUtility;
use Stringable;

final class Identifier implements Stringable
{
    public readonly IdentifierType $type;

    public readonly string $value;

    /**
     * @throws IdentifierException
     */
    public function __construct(
        string $identifier,
        private readonly AuthConfig $authConfig,
    ) {
        $this->type  = $this->detectType($identifier);
        $this->value = $this->normalize($identifier);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @throws IdentifierException
     */
    public static function fromString(?string $identifier): self
    {
        if (empty($identifier)) {
            throw IdentifierException::identifierIsRequired();
        }

        return resolve(self::class, ['identifier' => $identifier]);
    }

    public function equals(self $other): bool
    {
        return $this->type  === $other->type
            && $this->value === $other->value;
    }

    /**
     * @throws IdentifierException
     *
     * The detection logic based on the new rules
     */
    private function detectType(string $value): IdentifierType
    {
        if (str_contains($value, '@')) {
            if (! CustomValidator::isEmailValid($value)) {
                throw IdentifierException::invalidEmail();
            }

            return IdentifierType::Email;
        }

        if (is_numeric(StringUtility::numberToEn($value))) {
            $mobile = StringUtility::transformMobile($value);

            if (! CustomValidator::isMobileValid($mobile)) {
                throw IdentifierException::invalidMobile();
            }

            return IdentifierType::Mobile;
        }

        $usernameRegex = $this->authConfig->usernameValidationRegex;

        if (! $this->usernameIsValid($usernameRegex, $value)) {
            throw IdentifierException::invalidUsername();
        }

        return IdentifierType::Username;
    }

    private function usernameIsValid(string $regex, string $value): bool
    {
        return (bool) preg_match($regex, $value);
    }

    private function normalize(string $identifier): string
    {
        if ($this->type->isMobile()) {
            return StringUtility::transformMobile($identifier);
        }

        return strtolower(trim(StringUtility::numberToEn($identifier)));
    }
}
