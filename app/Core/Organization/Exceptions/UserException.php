<?php

declare(strict_types=1);

namespace App\Core\Organization\Exceptions;

use App\Exceptions\BusinessException;

class UserException extends BusinessException
{
    public static function selfDeletionPrevention(): self
    {
        return new self('شما نمی‌توانید حساب کاربری فعال خود را حذف کنید.');
    }
}
