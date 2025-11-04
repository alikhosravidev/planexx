<?php

declare(strict_types=1);

namespace App\Services\ImageService\DTOs;

use InvalidArgumentException;

final class ProviderConfig
{
    public readonly string $name;
    public readonly string $baseUrl;

    public function __construct(
        string $name,
        public readonly string $apiKey,
        string $baseUrl,
        public readonly array $options = [],
        public readonly int $timeout = 60,
        public readonly int $maxRetries = 3,
        public readonly bool $enabled = true,
        public readonly int $priority = 0,
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

    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
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