<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Facades;

use App\Services\AIImageService\AIImageService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AIImageService createWithOpenAI(string $apiKey, array $options = [])
 * @method static AIImageService createWithStabilityAI(string $apiKey, array $options = [])
 * @method static AIImageService createWithGemini(string $apiKey, array $options = [])
 * @method static AIImageService createWithSeedream(string $apiKey, array $options = [])
 * @method static AIImageService createWithMultipleProviders(array $providers)
 * @method static AIImageService createFromEnv()
 */
class AIImageServiceFactory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ai_image_service_factory';
    }
}
