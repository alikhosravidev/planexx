<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\StabilityAI;

use App\Services\AIImageService\Contracts\AbstractImageProvider;
use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\Contracts\ImageGeneratorInterface;

final class StabilityAIProvider extends AbstractImageProvider
{
    public function getName(): string
    {
        return 'stability-ai';
    }

    protected function createGenerator(): ImageGeneratorInterface
    {
        return new StabilityAIGenerator($this->httpClient, $this->logger, $this->config);
    }

    protected function createEditor(): ImageEditorInterface
    {
        return new StabilityAIEditor($this->httpClient, $this->logger, $this->config);
    }
}
