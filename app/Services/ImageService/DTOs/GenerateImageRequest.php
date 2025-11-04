<?php

declare(strict_types=1);

namespace App\Services\ImageService\DTOs;

use InvalidArgumentException;

final class GenerateImageRequest
{
    public readonly string  $prompt;
    public function __construct(
        string  $prompt,
        public readonly ?string $negativePrompt = null,
        public readonly ?string $memberProfile = null,
        public readonly int     $numberOfImages = 1,
        public readonly string  $size = '1024x1024',
        public readonly string  $style = 'natural',
        public readonly array   $colors = [],
        public readonly array   $metadata = [],
    ) {
        $this->prompt = trim($prompt);
        $this->validateInputs();
    }

    private function validateInputs(): void
    {
        if (empty(trim($this->prompt))) {
            throw new InvalidArgumentException('Prompt cannot be empty');
        }

        if ($this->numberOfImages < 1 || $this->numberOfImages > 4) {
            throw new InvalidArgumentException('Number of images must be between 1 and 4');
        }

        if (! preg_match('/^\d+x\d+$/', $this->size)) {
            throw new InvalidArgumentException('Invalid size format. Use format: WIDTHxHEIGHT');
        }
    }

    public function toArray(): array
    {
        return [
            'prompt' => $this->prompt,
            'negative_prompt' => $this->negativePrompt,
            'number_of_images' => $this->numberOfImages,
            'size' => $this->size,
            'style' => $this->style,
            'colors' => $this->colors,
            'member_profile' => $this->memberProfile,
            'metadata' => $this->metadata,
        ];
    }
}