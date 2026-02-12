<?php

declare(strict_types=1);

namespace Applications\Contracts\Exceptions;

use App\Exceptions\BusinessException;
use App\Exceptions\HttpStatusCodeEnum;

class WebApiException extends BusinessException
{
    private ?HttpStatusCodeEnum $customStatusCode = null;

    public static function fromApiResponse(string $message, int $statusCode, array $errors = []): self
    {
        $instance = new self($message, $errors);

        // Set the custom status code after construction
        $enumStatusCode = HttpStatusCodeEnum::tryFrom($statusCode)
            ?? HttpStatusCodeEnum::HTTP_BAD_REQUEST;

        $instance->customStatusCode = $enumStatusCode;
        $instance->code             = $enumStatusCode->value;

        return $instance;
    }

    protected function statusCode(): HttpStatusCodeEnum
    {
        return $this->customStatusCode ?? HttpStatusCodeEnum::HTTP_BAD_REQUEST;
    }
}
