<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseBuilder
{
    private int $statusCode = SymfonyResponse::HTTP_OK;
    private array $data = [];

    public function setData(array|object $data): self
    {
        $this->data['data'] = $data;
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

    public function success(array|object $data = [], ?string $message = null): JsonResponse
    {
        $this->setStatus(true)->setData($data);
        if ($message) {
            $this->setMessage($message);
        }
        return $this->build();
    }

    public function error(
        string $message,
        int    $statusCode = SymfonyResponse::HTTP_BAD_REQUEST,
        array  $errors = []
    ): JsonResponse {
        $this->setStatus(false)->setMessage($message)->setStatusCode($statusCode);
        if (!empty($errors)) {
            $this->setErrors($errors);
        }
        return $this->build();
    }

    public function created(array|object $data = [], ?string $message = null): JsonResponse
    {
        $this->setStatusCode(SymfonyResponse::HTTP_CREATED);
        return $this->success($data, $message);
    }
}
