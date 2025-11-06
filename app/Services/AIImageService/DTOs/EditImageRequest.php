<?php

declare(strict_types=1);

namespace App\Services\AIImageService\DTOs;

use InvalidArgumentException;

final readonly class EditImageRequest
{
    public string $imageUrl;
    public string $operation;

    public function __construct(
        string $imageUrl,
        string $operation,
        public ?string $prompt = null,
        public ?string $maskUrl = null,
        public array   $parameters = [],
        public array   $metadata = [],
    ) {
        $this->imageUrl = trim($imageUrl);
        $this->operation = strtolower(trim($operation));

        $this->validateInputs();
    }

    private function validateInputs(): void
    {
        if (!filter_var($this->imageUrl, FILTER_VALIDATE_URL) && !file_exists($this->imageUrl)) {
            throw new InvalidArgumentException('Invalid image URL or path');
        }

        $validOperations = ['inpaint', 'outpaint', 'upscale', 'variation', 'style_transfer', 'enhance'];
        if (!in_array($this->operation, $validOperations, true)) {
            throw new InvalidArgumentException('Invalid operation: ' . $this->operation);
        }

        if ($this->operation === 'inpaint' && empty($this->maskUrl)) {
            throw new InvalidArgumentException('Mask URL is required for inpaint operation');
        }
    }

    public function toArray(): array
    {
        return [
            'image_url' => $this->imageUrl,
            'operation' => $this->operation,
            'prompt' => $this->prompt,
            'mask_url' => $this->maskUrl,
            'parameters' => $this->parameters,
            'metadata' => $this->metadata,
        ];
    }
}
