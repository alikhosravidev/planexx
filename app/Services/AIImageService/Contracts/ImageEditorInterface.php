<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Contracts;

use App\Services\AIImageService\DTOs\EditImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;

interface ImageEditorInterface
{
    public function edit(EditImageRequest $request): ImageResponse;

    public function supportsOperation(string $operation): bool;
}
