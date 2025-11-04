<?php

declare(strict_types=1);

namespace App\Services\ImageService\DTOs;

use InvalidArgumentException;

final class EditImageRequest
{
    public readonly string $imageUrl;
    public readonly string $operation;

    public function __construct(
        string $imageUrl,
        string $operation,
        public readonly ?string $prompt = null,
        public readonly ?string $maskUrl = null,
        public readonly array $parameters = [],
        public readonly array $metadata = [],
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