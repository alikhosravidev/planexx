<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Contracts;

interface ImageProviderInterface
{
    public function getName(): string;

    public function isAvailable(): bool;

    public function getGenerator(): ImageGeneratorInterface;

    public function getEditor(): ImageEditorInterface;
}
