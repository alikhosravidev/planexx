<?php

declare(strict_types=1);

namespace App\Core\Notify\Services\SmsServiceProvider\Exceptions;

use App\Exceptions\BusinessException;

class SmsException extends BusinessException
{
    public static function failed(): self
    {
        return new self('مشکلی در هنگام ارسال اطلاعیه رخ داده است، لطفا با تیم پشتیبان تماس بگیرید.');
    }
}
