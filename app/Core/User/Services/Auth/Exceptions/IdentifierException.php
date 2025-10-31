<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Exceptions;

use App\Exceptions\BusinessException;

final class IdentifierException extends BusinessException
{
    public static function invalidMobile(): self
    {
        return new self(trans('user::errors.mobile_invalid'));
    }

    public static function invalidEmail(): self
    {
        return new self(trans('user::errors.email_invalid'));
    }

    public static function identifierIsRequired(): self
    {
        return new self(trans('user::errors.identifier_required'));
    }

    public static function invalidUsername(): self
    {
        return new self(trans('user::errors.username_format_invalid'));
    }
}
