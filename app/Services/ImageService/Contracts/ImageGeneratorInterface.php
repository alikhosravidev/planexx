<?php

declare(strict_types=1);

namespace App\Services\ImageService\Contracts;

use App\Services\ImageService\DTOs\GenerateImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;

interface ImageGeneratorInterface
{
    public function generate(GenerateImageRequest $request): ImageResponse;

    public function supports(string $feature): bool;
}