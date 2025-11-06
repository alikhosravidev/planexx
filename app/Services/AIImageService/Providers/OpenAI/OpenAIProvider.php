<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\OpenAI;

use App\Services\AIImageService\Contracts\AbstractImageProvider;
use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\Contracts\ImageGeneratorInterface;

final class OpenAIProvider extends AbstractImageProvider
{
    public function getName(): string
    {
        return 'openai';
    }

    protected function createGenerator(): ImageGeneratorInterface
    {
        return new OpenAIGenerator($this->httpClient, $this->logger, $this->config);
    }

    protected function createEditor(): ImageEditorInterface
    {
        return new OpenAIEditor($this->httpClient, $this->logger, $this->config);
    }
}
