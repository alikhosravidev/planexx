<?php

declare(strict_types=1);

namespace App\Core\User\Services\Auth\Exceptions;

use App\Exceptions\BusinessException;

final class AuthException extends BusinessException
{
    public static function invalidState(): self
    {
        return new self(trans('user::errors.invalid_auth_state'));
    }

    public static function credentialsInvalid(): self
    {
        return new self(trans('user::errors.credentials_invalid'));
    }

    public static function passwordIncorrect(): self
    {
        return new self(trans('user::errors.password_incorrect'));
    }

    public static function concurrentLoginLimit(int $loginLimitationCount): self
    {
        return new self(
            trans(
                'user::errors.concurrent_login_limit',
                ['login_limitation_count' => $loginLimitationCount]
            )
        );
    }

    public static function userNotRegistered(): self
    {
        return new self(trans('user::errors.user_not_registered'));
    }
}
