<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\ImageService\ImageServiceFactory;
use App\Services\ImageService\DTOs\GenerateImageRequest;

// ============================================
// Quick Start - Simple Usage Example
// ============================================

// Method 1: Simple - Single Provider (OpenAI)
$imageService = ImageServiceFactory::createWithOpenAI('your-api-key-here');

// Generate an image
$request = new GenerateImageRequest(
    prompt: 'A beautiful sunset over mountains',
    size: '1024x1024'
);

$response = $imageService->generate($request);

if ($response->success) {
    echo "✅ Success!\n";
    print_r($response->images);
} else {
    echo "❌ Error: {$response->error}\n";
}

// ============================================

// Method 2: From Environment Variables
// Make sure you have .env file with API keys
$imageService = ImageServiceFactory::createFromEnv();

$response = $imageService->generate($request);
echo json_encode($response->toArray(), JSON_PRETTY_PRINT);

// ============================================

// Method 3: Multiple Providers with Fallback
$imageService = ImageServiceFactory::createWithMultipleProviders([
    [
        'type' => 'openai',
        'api_key' => 'your-openai-key',
        'enabled' => true,
        'priority' => 1,
    ],
    [
        'type' => 'stability-ai',
        'api_key' => 'your-stability-key',
        'enabled' => true,
        'priority' => 2,
    ],
]);

// Will use the first available provider
$response = $imageService->generate($request);
