# Image Service API - AI Agent Guide

## Quick Start

```php
// Method 1: Single Provider
$service = ImageServiceFactory::createWithOpenAI('api-key');
$service = ImageServiceFactory::createWithGemini('api-key');
$service = ImageServiceFactory::createWithSeedream('api-key');
$service = ImageServiceFactory::createWithStabilityAI('api-key');

// Method 2: Auto-load from ENV
$service = ImageServiceFactory::createFromEnv();

// Method 3: Multiple Providers
$service = ImageServiceFactory::createWithMultipleProviders([
    ['type' => 'openai', 'api_key' => '...', 'priority' => 1],
    ['type' => 'gemini', 'api_key' => '...', 'priority' => 2],
]);
```

## Generate Image

```php
use App\Services\ImageService\DTOs\GenerateImageRequest;

$request = new GenerateImageRequest(
    prompt: 'your prompt here',
    negativePrompt: 'optional',
    numberOfImages: 1,
    size: '1024x1024',
    style: 'natural', // natural, vivid, anime, cinematic
    colors: ['blue', 'red'],
    metadata: ['user_id' => 123]
);

$response = $service->generate($request);
// or specify provider: $service->generate($request, 'gemini');

if ($response->success) {
    foreach ($response->images as $image) {
        $url = $image['url'] ?? null;
        $base64 = $image['base64'] ?? null;
    }
}
```

## Edit Image

```php
use App\Services\ImageService\DTOs\EditImageRequest;

$request = new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'inpaint', // see operations below
    prompt: 'optional prompt',
    maskUrl: 'https://example.com/mask.png', // required for inpaint
    parameters: ['scale_factor' => 4]
);

$response = $service->edit($request);
```

## Supported Operations by Provider

| Operation | OpenAI | StabilityAI | Gemini | Seedream |
|-----------|--------|-------------|--------|----------|
| generate | ✅ | ✅ | ✅ | ✅ |
| inpaint | ✅ | ✅ | ❌ | ✅ |
| outpaint | ❌ | ✅ | ❌ | ✅ |
| upscale | ❌ | ✅ | ❌ | ✅ |
| variation | ✅ | ❌ | ✅ | ✅ |
| enhance | ❌ | ❌ | ✅ | ✅ |
| style_transfer | ❌ | ❌ | ✅ | ❌ |

## Operation Examples

### Inpaint
```php
new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'inpaint',
    prompt: 'add a red car',
    maskUrl: 'https://example.com/mask.png'
);
```

### Outpaint
```php
new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'outpaint',
    prompt: 'extend naturally',
    parameters: ['direction' => 'all', 'expansion_ratio' => 1.5]
);
```

### Upscale
```php
new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'upscale',
    parameters: ['scale_factor' => 4, 'enhance_details' => true]
);
```

### Variation
```php
new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'variation',
    parameters: ['num_variations' => 3]
);
```

### Enhance
```php
new EditImageRequest(
    imageUrl: 'https://example.com/image.jpg',
    operation: 'enhance',
    prompt: 'improve quality and colors'
);
```

## Response Structure

```php
$response->success;        // bool
$response->images;         // array of images
$response->error;          // string|null
$response->provider;       // string (which provider was used)
$response->processingTime; // float (seconds)
$response->metadata;       // array
$response->jobId;          // string|null

// Convert to array/json
$response->toArray();
$response->toJson();
```

## REST API Endpoints

### POST /api/generate
```json
{
  "prompt": "a beautiful sunset",
  "size": "1024x1024",
  "number_of_images": 1,
  "style": "natural",
  "provider": "gemini"
}
```

### POST /api/edit
```json
{
  "image_url": "https://example.com/image.jpg",
  "operation": "upscale",
  "parameters": {
    "scale_factor": 4
  },
  "provider": "seedream"
}
```

### GET /api/providers
Returns list of available providers

### GET /api/health
Health check endpoint

## Environment Variables

```env
OPENAI_API_KEY=sk-...
OPENAI_MODEL=dall-e-3

GEMINI_API_KEY=...
GEMINI_MODEL=gemini-2.5-flash

SEEDREAM_API_KEY=...
SEEDREAM_MODEL=seedream-4

STABILITY_API_KEY=...
STABILITY_ENGINE=stable-diffusion-xl-1024-v1-0
```

## Provider Selection Logic

1. If provider name specified → use that provider
2. If multiple providers registered → use first available (by priority)
3. If provider unavailable → throw ProviderException

## Error Handling

```php
try {
    $response = $service->generate($request);
    if (!$response->success) {
        // Handle error: $response->error
    }
} catch (\App\Services\ImageService\Exceptions\ProviderException $e) {
    // Provider not found or unavailable
} catch (\App\Services\ImageService\Exceptions\HttpException $e) {
    // HTTP request failed
} catch (\InvalidArgumentException $e) {
    // Invalid input parameters
}
```

## Best Practices for AI Agents

1. **Always validate inputs** before creating requests
2. **Use try-catch** for error handling
3. **Check response->success** before accessing images
4. **Specify provider** when you need specific features
5. **Use metadata** to track requests
6. **Handle both url and base64** image formats

## Common Use Cases

### Case 1: Generate with fallback
```php
$service = ImageServiceFactory::createWithMultipleProviders([
    ['type' => 'seedream', 'api_key' => '...', 'priority' => 1],
    ['type' => 'openai', 'api_key' => '...', 'priority' => 2],
]);
$response = $service->generate($request); // Auto-fallback
```

### Case 2: High-quality upscale
```php
$service = ImageServiceFactory::createWithSeedream('api-key');
$request = new EditImageRequest(
    imageUrl: $imageUrl,
    operation: 'upscale',
    parameters: ['scale_factor' => 4, 'enhance_details' => true]
);
```

### Case 3: Style transfer
```php
$service = ImageServiceFactory::createWithGemini('api-key');
$request = new EditImageRequest(
    imageUrl: $imageUrl,
    operation: 'style_transfer',
    prompt: 'convert to oil painting style'
);
```

## Size Formats

- Standard: `1024x1024`, `512x512`, `256x256`
- Wide: `1792x1024`, `1024x1792`
- Custom: `{width}x{height}` (check provider limits)

## Style Options

- `natural` - Realistic, natural looking
- `vivid` - Vibrant, enhanced colors
- `anime` - Anime/manga style
- `cinematic` - Movie-like quality
- `photographic` - Photo-realistic

## Tips for Optimal Results

1. **Seedream**: Best for speed and upscaling
2. **Gemini**: Best for multimodal and style transfer
3. **OpenAI**: Best for creative generation
4. **StabilityAI**: Best for fine-tuned control

---

**File Locations:**
- Main Service: `App/Services/ImageService/ImageService.php`
- Factory: `App/Services/ImageService/ImageServiceFactory.php`
- DTOs: `App/Services/ImageService/DTOs/`
- Providers: `App/Services/ImageService/Providers/`
