<?php

declare(strict_types=1);

namespace App\Core\Organization\Services\Auth\Exceptions;

use App\Exceptions\BusinessException;

final class IdentifierException extends BusinessException
{
    public static function invalidMobile(): self
    {
        return new self(trans('Organization::errors.mobile_invalid'));
    }

    public static function invalidEmail(): self
    {
        return new self(trans('Organization::errors.email_invalid'));
    }

    public static function identifierIsRequired(): self
    {
        return new self(trans('Organization::errors.identifier_required'));
    }

    public static function invalidUsername(): self
    {
        return new self(trans('Organization::errors.username_format_invalid'));
    }
}
