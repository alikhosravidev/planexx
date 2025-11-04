<?php

declare(strict_types=1);

namespace App\Services\ImageService;

use App\Services\ImageService\Providers\OpenAI\OpenAIProvider;
use App\Services\ImageService\Providers\StabilityAI\StabilityAIProvider;
use App\Services\ImageService\Providers\Gemini\GeminiProvider;
use App\Services\ImageService\Providers\Seedream\SeedreamProvider;
use App\Services\ImageService\DTOs\ProviderConfig;

/**
 * Factory class to simplify ImageService creation with common providers
 */
final class ImageServiceFactory
{
    /**
     * Create ImageService with OpenAI provider
     */
    public static function createWithOpenAI(
        string $apiKey,
        array $options = []
    ): ImageService {
        $service = new ImageService();
        
        $config = new ProviderConfig(
            name: 'openai',
            apiKey: $apiKey,
            baseUrl: 'https://api.openai.com',
            options: array_merge([
                'model' => 'dall-e-3',
                'quality' => 'standard',
            ], $options),
            timeout: $options['timeout'] ?? 120,
            enabled: true
        );

        $service->registerProvider(new OpenAIProvider($config));
        
        return $service;
    }

    /**
     * Create ImageService with Stability AI provider
     */
    public static function createWithStabilityAI(
        string $apiKey,
        array $options = []
    ): ImageService {
        $service = new ImageService();
        
        $config = new ProviderConfig(
            name: 'stability-ai',
            apiKey: $apiKey,
            baseUrl: 'https://api.stability.ai',
            options: array_merge([
                'engine' => 'stable-diffusion-xl-1024-v1-0',
                'cfg_scale' => 7,
                'steps' => 30,
            ], $options),
            timeout: $options['timeout'] ?? 120,
            enabled: true
        );

        $service->registerProvider(new StabilityAIProvider($config));
        
        return $service;
    }

    /**
     * Create ImageService with Gemini provider
     */
    public static function createWithGemini(
        string $apiKey,
        array $options = []
    ): ImageService {
        $service = new ImageService();
        
        $config = new ProviderConfig(
            name: 'gemini',
            apiKey: $apiKey,
            baseUrl: 'https://generativelanguage.googleapis.com',
            options: array_merge([
                'model' => 'gemini-2.5-flash',
                'temperature' => 0.9,
                'top_k' => 40,
                'top_p' => 0.95,
                'max_tokens' => 8192,
            ], $options),
            timeout: $options['timeout'] ?? 120,
            enabled: true
        );

        $service->registerProvider(new GeminiProvider($config));
        
        return $service;
    }

    /**
     * Create ImageService with Seedream provider
     */
    public static function createWithSeedream(
        string $apiKey,
        array $options = []
    ): ImageService {
        $service = new ImageService();
        
        $config = new ProviderConfig(
            name: 'seedream',
            apiKey: $apiKey,
            baseUrl: 'https://api.seedream.ai',
            options: array_merge([
                'model' => 'seedream-4',
                'steps' => 50,
                'guidance_scale' => 7.5,
                'quality' => 'high',
                'sampler' => 'dpmpp_2m',
            ], $options),
            timeout: $options['timeout'] ?? 120,
            enabled: true
        );

        $service->registerProvider(new SeedreamProvider($config));
        
        return $service;
    }

    /**
     * Create ImageService with multiple providers
     */
    public static function createWithMultipleProviders(array $providers): ImageService
    {
        $service = new ImageService();

        foreach ($providers as $providerConfig) {
            $type = $providerConfig['type'] ?? null;
            $apiKey = $providerConfig['api_key'] ?? null;
            $options = $providerConfig['options'] ?? [];

            if (!$apiKey) {
                continue;
            }

            match ($type) {
                'openai' => $service->registerProvider(
                    new OpenAIProvider(new ProviderConfig(
                        name: 'openai',
                        apiKey: $apiKey,
                        baseUrl: 'https://api.openai.com',
                        options: $options,
                        timeout: $options['timeout'] ?? 120,
                        enabled: $providerConfig['enabled'] ?? true,
                        priority: $providerConfig['priority'] ?? 0
                    ))
                ),
                'stability-ai' => $service->registerProvider(
                    new StabilityAIProvider(new ProviderConfig(
                        name: 'stability-ai',
                        apiKey: $apiKey,
                        baseUrl: 'https://api.stability.ai',
                        options: $options,
                        timeout: $options['timeout'] ?? 120,
                        enabled: $providerConfig['enabled'] ?? true,
                        priority: $providerConfig['priority'] ?? 0
                    ))
                ),
                'gemini' => $service->registerProvider(
                    new GeminiProvider(new ProviderConfig(
                        name: 'gemini',
                        apiKey: $apiKey,
                        baseUrl: 'https://generativelanguage.googleapis.com',
                        options: $options,
                        timeout: $options['timeout'] ?? 120,
                        enabled: $providerConfig['enabled'] ?? true,
                        priority: $providerConfig['priority'] ?? 0
                    ))
                ),
                'seedream' => $service->registerProvider(
                    new SeedreamProvider(new ProviderConfig(
                        name: 'seedream',
                        apiKey: $apiKey,
                        baseUrl: 'https://api.seedream.ai',
                        options: $options,
                        timeout: $options['timeout'] ?? 120,
                        enabled: $providerConfig['enabled'] ?? true,
                        priority: $providerConfig['priority'] ?? 0
                    ))
                ),
                default => null
            };
        }

        return $service;
    }

    /**
     * Create ImageService from environment variables
     */
    public static function createFromEnv(): ImageService
    {
        $providers = [];

        // OpenAI
        if ($openAIKey = getenv('OPENAI_API_KEY')) {
            $providers[] = [
                'type' => 'openai',
                'api_key' => $openAIKey,
                'options' => [
                    'model' => getenv('OPENAI_MODEL') ?: 'dall-e-3',
                    'quality' => getenv('OPENAI_QUALITY') ?: 'standard',
                ],
                'enabled' => true,
                'priority' => 1,
            ];
        }

        // Stability AI
        if ($stabilityKey = getenv('STABILITY_API_KEY')) {
            $providers[] = [
                'type' => 'stability-ai',
                'api_key' => $stabilityKey,
                'options' => [
                    'engine' => getenv('STABILITY_ENGINE') ?: 'stable-diffusion-xl-1024-v1-0',
                    'cfg_scale' => (int)(getenv('STABILITY_CFG_SCALE') ?: 7),
                    'steps' => (int)(getenv('STABILITY_STEPS') ?: 30),
                ],
                'enabled' => true,
                'priority' => 2,
            ];
        }

        // Gemini
        if ($geminiKey = getenv('GEMINI_API_KEY')) {
            $providers[] = [
                'type' => 'gemini',
                'api_key' => $geminiKey,
                'options' => [
                    'model' => getenv('GEMINI_MODEL') ?: 'gemini-2.5-flash',
                    'temperature' => (float)(getenv('GEMINI_TEMPERATURE') ?: 0.9),
                    'top_k' => (int)(getenv('GEMINI_TOP_K') ?: 40),
                    'top_p' => (float)(getenv('GEMINI_TOP_P') ?: 0.95),
                    'max_tokens' => (int)(getenv('GEMINI_MAX_TOKENS') ?: 8192),
                ],
                'enabled' => true,
                'priority' => 3,
            ];
        }

        // Seedream
        if ($seedreamKey = getenv('SEEDREAM_API_KEY')) {
            $providers[] = [
                'type' => 'seedream',
                'api_key' => $seedreamKey,
                'options' => [
                    'model' => getenv('SEEDREAM_MODEL') ?: 'seedream-4',
                    'steps' => (int)(getenv('SEEDREAM_STEPS') ?: 50),
                    'guidance_scale' => (float)(getenv('SEEDREAM_GUIDANCE_SCALE') ?: 7.5),
                    'quality' => getenv('SEEDREAM_QUALITY') ?: 'high',
                    'sampler' => getenv('SEEDREAM_SAMPLER') ?: 'dpmpp_2m',
                ],
                'enabled' => true,
                'priority' => 4,
            ];
        }

        return self::createWithMultipleProviders($providers);
    }
}
