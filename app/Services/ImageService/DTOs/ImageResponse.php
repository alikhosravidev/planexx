<?php

declare(strict_types=1);

namespace App\Services\ImageService\DTOs;

final class ImageResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly array $images = [],
        public readonly ?string $error = null,
        public readonly array $metadata = [],
        public readonly float $processingTime = 0.0,
        public readonly string $provider = '',
        public readonly ?string $jobId = null,
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'images' => $this->images,
            'error' => $this->error,
            'metadata' => $this->metadata,
            'processing_time' => round($this->processingTime, 3),
            'provider' => $this->provider,
            'job_id' => $this->jobId,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function success(
        array $images,
        array $metadata = [],
        float $processingTime = 0.0,
        string $provider = '',
        ?string $jobId = null
    ): self {
        return new self(
            success: true,
            images: $images,
            metadata: $metadata,
            processingTime: $processingTime,
            provider: $provider,
            jobId: $jobId
        );
    }

    public static function failure(
        string $error,
        array $metadata = [],
        float $processingTime = 0.0,
        string $provider = ''
    ): self {
        return new self(
            success: false,
            error: $error,
            metadata: $metadata,
            processingTime: $processingTime,
            provider: $provider
        );
    }
}