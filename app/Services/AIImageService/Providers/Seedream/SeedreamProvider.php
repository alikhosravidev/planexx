<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\Seedream;

use App\Services\AIImageService\Contracts\AbstractImageProvider;
use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\Contracts\ImageGeneratorInterface;

final class SeedreamProvider extends AbstractImageProvider
{
    public function getName(): string
    {
        return 'seedream';
    }

    protected function createGenerator(): ImageGeneratorInterface
    {
        return new SeedreamGenerator($this->httpClient, $this->logger, $this->config);
    }

    protected function createEditor(): ImageEditorInterface
    {
        return new SeedreamEditor($this->httpClient, $this->logger, $this->config);
    }
}
