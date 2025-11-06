<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Contracts;

use App\Services\AIImageService\DTOs\GenerateImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;

interface ImageGeneratorInterface
{
    public function generate(GenerateImageRequest $request): ImageResponse;

    public function supports(string $feature): bool;
}
