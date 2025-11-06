<?php

declare(strict_types=1);

namespace App\Services\AIImageService\DTOs;

final readonly class ImageResponse
{
    public function __construct(
        public bool $success,
        public array $images = [],
        public ?string $error = null,
        public array $metadata = [],
        public float $processingTime = 0.0,
        public string $provider = '',
        public ?string $jobId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'success'         => $this->success,
            'images'          => $this->images,
            'error'           => $this->error,
            'metadata'        => $this->metadata,
            'processing_time' => round($this->processingTime, 3),
            'provider'        => $this->provider,
            'job_id'          => $this->jobId,
        ];
    }
}
