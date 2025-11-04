<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\StabilityAI;

use App\Services\ImageService\Contracts\AbstractImageProvider;
use App\Services\ImageService\Contracts\ImageEditorInterface;
use App\Services\ImageService\Contracts\ImageGeneratorInterface;

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
