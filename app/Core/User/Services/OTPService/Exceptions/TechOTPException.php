<?php

declare(strict_types=1);

namespace App\Core\User\Services\OTPService\Exceptions;

use App\Exceptions\TechnicalException;

class TechOTPException extends TechnicalException
{
    public static function otpDisabled(): self
    {
        return new self(
            trans('user::errors.otp_system_disabled')
        );
    }
}
