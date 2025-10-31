<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\User\Services\OTPService\Exceptions;

use App\Exceptions\BusinessException;

class OTPException extends BusinessException
{
    public static function incorrect(): self
    {
        return new self(trans('user::errors.invalid_otp_code'));
    }

    public static function invalidChannel(): self
    {
        return new static(
            trans('user::errors.no_valid_channel')
        );
    }

    public static function sendingCodeFailed(): self
    {
        return new self(
            trans('user::errors.otp_sending_failed')
        );
    }

    public static function invalidOtpRecipientIdentifier(): self
    {
        return new self(
            trans('user::errors.otp_identifier_invalid')
        );
    }
}
