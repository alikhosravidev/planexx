<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\Gemini;

use App\Services\ImageService\Contracts\AbstractImageProvider;
use App\Services\ImageService\Contracts\ImageEditorInterface;
use App\Services\ImageService\Contracts\ImageGeneratorInterface;

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
