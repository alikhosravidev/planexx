<?php

declare(strict_types=1);

namespace App\Services\AIImageService\DTOs;

use InvalidArgumentException;

final readonly class ProviderConfig
{
    public string $name;
    public string $baseUrl;

    public function __construct(
        string        $name,
        public string $apiKey,
        string        $baseUrl,
        public array  $options = [],
        public int    $timeout = 60,
        public int    $maxRetries = 3,
        public bool   $enabled = true,
        public int    $priority = 0,
    ) {
        $this->name = trim($name);
        $this->baseUrl = rtrim($baseUrl, '/');

        $this->validateInputs();
    }

    private function validateInputs(): void
    {
        if (empty($this->name)) {
            throw new InvalidArgumentException('Provider name cannot be empty');
        }

        if (empty($this->apiKey)) {
            throw new InvalidArgumentException('API key cannot be empty');
        }

        if (!filter_var($this->baseUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid base URL');
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'api_key' => $this->apiKey,
            'base_url' => $this->baseUrl,
            'options' => $this->options,
            'timeout' => $this->timeout,
            'max_retries' => $this->maxRetries,
            'enabled' => $this->enabled,
            'priority' => $this->priority,
        ];
    }
}
