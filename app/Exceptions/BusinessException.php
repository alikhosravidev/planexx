<?php

declare(strict_types=1);

namespace App\Exceptions;

abstract class BusinessException extends BaseException
{
    protected function statusCode(): HttpStatusCodeEnum
    {
        return HttpStatusCodeEnum::HTTP_PRECONDITION_REQUIRED;
    }
}
