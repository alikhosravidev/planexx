<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Facades;

use App\Services\AIImageService\AIImageService;
use App\Services\AIImageService\AIImageServiceFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AIImageService createWithOpenAI(string $apiKey, array $options = [])
 * @method static AIImageService createWithStabilityAI(string $apiKey, array $options = [])
 * @method static AIImageService createWithGemini(string $apiKey, array $options = [])
 * @method static AIImageService createWithSeedream(string $apiKey, array $options = [])
 * @method static AIImageService createWithMultipleProviders(array $providers)
 * @method static AIImageService createFromEnv()
 */
class AIImageFactory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AIImageServiceFactory::class;
    }
}
