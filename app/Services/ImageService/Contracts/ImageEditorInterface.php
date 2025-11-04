<?php

declare(strict_types=1);

namespace App\Services\ImageService\Contracts;

use App\Services\ImageService\DTOs\EditImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;

interface ImageEditorInterface
{
    public function edit(EditImageRequest $request): ImageResponse;

    public function supportsOperation(string $operation): bool;
}