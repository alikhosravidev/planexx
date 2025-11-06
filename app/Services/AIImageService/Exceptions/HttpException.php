<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Exceptions;

class HttpException extends ImageServiceException
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?string $responseBody = null
    ) {
        parent::__construct($message);
    }
}
