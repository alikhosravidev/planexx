<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\Gemini;

use App\Services\AIImageService\Contracts\AbstractImageProvider;
use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\Contracts\ImageGeneratorInterface;

final class GeminiProvider extends AbstractImageProvider
{
    public function getName(): string
    {
        return 'gemini';
    }

    protected function createGenerator(): ImageGeneratorInterface
    {
        return new GeminiGenerator($this->httpClient, $this->logger, $this->config);
    }

    protected function createEditor(): ImageEditorInterface
    {
        return new GeminiEditor($this->httpClient, $this->logger, $this->config);
    }
}
