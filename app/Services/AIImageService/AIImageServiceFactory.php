<?php

declare(strict_types=1);

namespace App\Services\AIImageService;

use App\Services\AIImageService\DTOs\ProviderConfig;
use App\Services\AIImageService\Providers\Gemini\GeminiProvider;
use App\Services\AIImageService\Providers\OpenAI\OpenAIProvider;
use App\Services\AIImageService\Providers\Seedream\SeedreamProvider;
use App\Services\AIImageService\Providers\StabilityAI\StabilityAIProvider;
use App\Services\HttpClient;
use Psr\Log\LoggerInterface;

/**
 * Factory class to simplify ImageService creation with common providers
 */
final class AIImageServiceFactory
{
    private const PROVIDERS = [
        'openai'       => OpenAIProvider::class,
        'stability-ai' => StabilityAIProvider::class,
        'gemini'       => GeminiProvider::class,
        'seedream'     => SeedreamProvider::class,
    ];

    public function __construct(
        private readonly AIImageService $imageService,
        private readonly LoggerInterface $logger,
        private readonly HttpClient $httpClient
    ) {
    }

    /**
     * Create ImageService with OpenAI provider
     */
    public function createWithOpenAI(string $apiKey, array $options = []): AIImageService
    {
        return $this->createServiceWithProvider('openai', $apiKey, $options);
    }

    /**
     * Create ImageService with Stability AI provider
     */
    public function createWithStabilityAI(string $apiKey, array $options = []): AIImageService
    {
        return $this->createServiceWithProvider('stability-ai', $apiKey, $options);
    }

    /**
     * Create ImageService with Gemini provider
     */
    public function createWithGemini(string $apiKey, array $options = []): AIImageService
    {
        return $this->createServiceWithProvider('gemini', $apiKey, $options);
    }

    /**
     * Create ImageService with Seedream provider
     */
    public function createWithSeedream(string $apiKey, array $options = []): AIImageService
    {
        return $this->createServiceWithProvider('seedream', $apiKey, $options);
    }

    /**
     * Create ImageService with multiple providers
     */
    public function createWithMultipleProviders(array $providers): AIImageService
    {
        $service = clone $this->imageService;

        foreach ($providers as $providerConfig) {
            $type    = $providerConfig['type']    ?? null;
            $apiKey  = $providerConfig['api_key'] ?? null;
            $options = $providerConfig['options'] ?? [];

            if (!$apiKey || !isset(self::PROVIDERS[$type])) {
                continue;
            }

            $config = new ProviderConfig(
                name: $type,
                apiKey: $apiKey,
                baseUrl: config("ai_image_service.providers.{$type}.base_url"),
                options: $options,
                timeout: config("ai_image_service.providers.{$type}.timeout"),
                enabled: $providerConfig['enabled']   ?? true,
                priority: $providerConfig['priority'] ?? config("ai_image_service.priorities.{$type}")
            );

            $service->registerProvider(
                new (self::PROVIDERS[$type])($config, $this->httpClient, $this->logger)
            );
        }

        return $service;
    }

    /**
     * Create ImageService from environment variables
     */
    public function createFromEnv(): AIImageService
    {
        $providers = array_filter([
            $this->getProviderFromEnv('openai', 'OPENAI_API_KEY', [
                'model'   => getenv('OPENAI_MODEL'),
                'quality' => getenv('OPENAI_QUALITY'),
            ]),
            $this->getProviderFromEnv('stability-ai', 'STABILITY_API_KEY', [
                'engine'    => getenv('STABILITY_ENGINE'),
                'cfg_scale' => getenv('STABILITY_CFG_SCALE'),
                'steps'     => getenv('STABILITY_STEPS'),
            ]),
            $this->getProviderFromEnv('gemini', 'GEMINI_API_KEY', [
                'model'       => getenv('GEMINI_MODEL'),
                'temperature' => getenv('GEMINI_TEMPERATURE'),
                'top_k'       => getenv('GEMINI_TOP_K'),
                'top_p'       => getenv('GEMINI_TOP_P'),
                'max_tokens'  => getenv('GEMINI_MAX_TOKENS'),
            ]),
            $this->getProviderFromEnv('seedream', 'SEEDREAM_API_KEY', [
                'model'          => getenv('SEEDREAM_MODEL'),
                'steps'          => getenv('SEEDREAM_STEPS'),
                'guidance_scale' => getenv('SEEDREAM_GUIDANCE_SCALE'),
                'quality'        => getenv('SEEDREAM_QUALITY'),
                'sampler'        => getenv('SEEDREAM_SAMPLER'),
            ]),
        ]);

        return $this->createWithMultipleProviders($providers);
    }

    /**
     * Create service with a specific provider
     */
    private function createServiceWithProvider(string $providerName, string $apiKey, array $options = []): AIImageService
    {
        $service = clone $this->imageService;

        $config = new ProviderConfig(
            name: $providerName,
            apiKey: $apiKey,
            baseUrl: config("ai_image_service.providers.{$providerName}.base_url"),
            options: array_merge(config("ai_image_service.providers.{$providerName}.defaults"), $options),
            timeout: config("ai_image_service.providers.{$providerName}.timeout"),
            enabled: true
        );

        $service->registerProvider(
            new (self::PROVIDERS[$providerName])($config, $this->httpClient, $this->logger)
        );

        return $service;
    }

    /**
     * Get provider config from environment variables
     */
    private function getProviderFromEnv(string $type, string $apiKeyEnv, array $envMappings): ?array
    {
        $apiKey = getenv($apiKeyEnv);

        if (!$apiKey) {
            return null;
        }

        $options = array_merge(
            config("ai_image_service.providers.{$type}.defaults"),
            array_filter($envMappings, fn ($value) => $value !== false && $value !== null)
        );

        return [
            'type'     => $type,
            'api_key'  => $apiKey,
            'options'  => $options,
            'enabled'  => true,
            'priority' => config("ai_image_service.priorities.{$type}"),
        ];
    }
}
