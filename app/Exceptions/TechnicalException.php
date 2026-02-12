<?php

declare(strict_types=1);

namespace App\Exceptions;

abstract class TechnicalException extends BaseException
{
    protected function statusCode(): HttpStatusCodeEnum
    {
        return HttpStatusCodeEnum::HTTP_BAD_REQUEST;
    }
}
