<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseBuilder
{
    private int $statusCode = SymfonyResponse::HTTP_OK;
    private array $data = [];

    public function setResult(array|object $result): self
    {
        $this->data['result'] = $result;
        return $this;
    }

    public function setMeta(array $meta): self
    {
        $this->data['meta'] = $meta;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->data['message'] = $message;
        return $this;
    }

    public function setStatus(bool $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setErrors(array $errors): self
    {
        $this->data['errors'] = $errors;
        return $this;
    }

    public function setError(string $code, string $message, ?array $details = null): self
    {
        $this->data['error'] = [
            'code' => $code,
            'message' => $message,
        ];
        if ($details) {
            $this->data['error']['details'] = $details;
        }
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        if ($statusCode >= 400) {
            $this->setStatus(false);
        }
        return $this;
    }

    public function build(): JsonResponse
    {
        return response()->json($this->data, $this->statusCode);
    }

    public function success(array|object $result = [], ?string $message = null, ?array $meta = null): JsonResponse
    {
        $this->setStatus(true)->setResult($result);
        if ($message) {
            $this->setMessage($message);
        }
        if ($meta) {
            $this->setMeta($meta);
        }
        return $this->build();
    }

    public function error(
        string $message,
        int    $statusCode = SymfonyResponse::HTTP_BAD_REQUEST,
        array  $errors = [],
        ?string $code = null
    ): JsonResponse {
        $this->setStatus(false)->setError($code ?? 'error', $message, $errors)->setStatusCode($statusCode);
        return $this->build();
    }

    public function created(array|object $result = [], ?string $message = null, ?array $meta = null): JsonResponse
    {
        $this->setStatusCode(SymfonyResponse::HTTP_CREATED);
        return $this->success($result, $message, $meta);
    }
}
