<?php

declare(strict_types=1);

namespace App\Services\ImageService\Contracts;

use App\Services\HttpClient;
use App\Services\ImageService\DTOs\ProviderConfig;
use App\Services\Logger;

abstract class AbstractImageProvider implements ImageProviderInterface
{
    protected readonly HttpClient $httpClient;
    protected readonly Logger $logger;
    protected ?ImageGeneratorInterface $generator = null;
    protected ?ImageEditorInterface $editor = null;

    public function __construct(
        protected readonly ProviderConfig $config
    ) {
        $this->httpClient = new HttpClient($config->timeout);
        $this->logger = new Logger($config->name);
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