<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\ImageService\DTOs;

use App\Services\AIImageService\DTOs\ProviderConfig;
use InvalidArgumentException;
use Tests\PureUnitTestBase;

class ProviderConfigTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $name       = 'openai';
        $apiKey     = 'sk-test123';
        $baseUrl    = 'https://api.openai.com/v1';
        $options    = ['model' => 'dall-e-3'];
        $timeout    = 120;
        $maxRetries = 5;
        $enabled    = true;
        $priority   = 1;

        $dto = new ProviderConfig($name, $apiKey, $baseUrl, $options, $timeout, $maxRetries, $enabled, $priority);

        $this->assertSame(trim($name), $dto->name);
        $this->assertSame($apiKey, $dto->apiKey);
        $this->assertSame(rtrim($baseUrl, '/'), $dto->baseUrl);
        $this->assertSame($options, $dto->options);
        $this->assertSame($timeout, $dto->timeout);
        $this->assertSame($maxRetries, $dto->maxRetries);
        $this->assertSame($enabled, $dto->enabled);
        $this->assertSame($priority, $dto->priority);
    }

    public function test_constructs_successfully_with_minimal_parameters(): void
    {
        $name    = 'stability-ai';
        $apiKey  = 'sk-stability123';
        $baseUrl = 'https://api.stability.ai';

        $dto = new ProviderConfig($name, $apiKey, $baseUrl);

        $this->assertSame(trim($name), $dto->name);
        $this->assertSame($apiKey, $dto->apiKey);
        $this->assertSame(rtrim($baseUrl, '/'), $dto->baseUrl);
        $this->assertSame([], $dto->options);
        $this->assertSame(60, $dto->timeout);
        $this->assertSame(3, $dto->maxRetries);
        $this->assertTrue($dto->enabled);
        $this->assertSame(0, $dto->priority);
    }

    public function test_throws_exception_for_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provider name cannot be empty');

        new ProviderConfig('', 'api-key', 'https://example.com');
    }

    public function test_throws_exception_for_whitespace_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provider name cannot be empty');

        new ProviderConfig('   ', 'api-key', 'https://example.com');
    }

    public function test_throws_exception_for_empty_api_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API key cannot be empty');

        new ProviderConfig('provider', '', 'https://example.com');
    }

    public function test_throws_exception_for_invalid_base_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base URL');

        new ProviderConfig('provider', 'api-key', 'invalid-url');
    }

    public function test_to_array_returns_correct_array(): void
    {
        $name       = 'gemini';
        $apiKey     = 'gemini-key123';
        $baseUrl    = 'https://generativelanguage.googleapis.com/v1';
        $options    = ['model' => 'gemini-2.5-flash'];
        $timeout    = 90;
        $maxRetries = 2;
        $enabled    = false;
        $priority   = 3;

        $dto   = new ProviderConfig($name, $apiKey, $baseUrl, $options, $timeout, $maxRetries, $enabled, $priority);
        $array = $dto->toArray();

        $expected = [
            'name'        => trim($name),
            'api_key'     => $apiKey,
            'base_url'    => rtrim($baseUrl, '/'),
            'options'     => $options,
            'timeout'     => $timeout,
            'max_retries' => $maxRetries,
            'enabled'     => $enabled,
            'priority'    => $priority,
        ];

        $this->assertSame($expected, $array);
    }
}
