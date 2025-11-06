<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Contracts;

use App\Services\HttpClient;
use App\Services\AIImageService\DTOs\ProviderConfig;
use Psr\Log\LoggerInterface;

abstract class AbstractImageProvider implements ImageProviderInterface
{
    protected readonly HttpClient $httpClient;
    protected readonly LoggerInterface $logger;
    protected ?ImageGeneratorInterface $generator = null;
    protected ?ImageEditorInterface $editor = null;

    public function __construct(
        protected readonly ProviderConfig $config,
        HttpClient $httpClient,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    abstract public function getName(): string;

    abstract protected function createGenerator(): ImageGeneratorInterface;

    abstract protected function createEditor(): ImageEditorInterface;

    public function getGenerator(): ImageGeneratorInterface
    {
        return $this->generator ??= $this->createGenerator();
    }

    public function getEditor(): ImageEditorInterface
    {
        return $this->editor ??= $this->createEditor();
    }

    public function isAvailable(): bool
    {
        return $this->config->enabled && !empty($this->config->apiKey);
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
            'Content-Type' => 'application/json',
        ];
    }
}
