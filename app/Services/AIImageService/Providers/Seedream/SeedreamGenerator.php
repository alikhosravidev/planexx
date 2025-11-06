<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\Seedream;

use App\Services\AIImageService\Contracts\ImageGeneratorInterface;
use App\Services\AIImageService\DTOs\GenerateImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;
use App\Services\AIImageService\DTOs\ProviderConfig;
use App\Services\HttpClient;
use Psr\Log\LoggerInterface;

final class SeedreamGenerator implements ImageGeneratorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly LoggerInterface $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function generate(GenerateImageRequest $request): ImageResponse
    {
        $model    = $this->config->getOption('model', 'seedream-4');
        $endpoint = $this->config->baseUrl . '/v1/generate';

        // Parse size
        [$width, $height] = explode('x', $request->size);

        $payload = [
            'model'          => $model,
            'prompt'         => $request->prompt,
            'width'          => (int)$width,
            'height'         => (int)$height,
            'num_images'     => $request->numberOfImages,
            'steps'          => $this->config->getOption('steps', 50),
            'guidance_scale' => $this->config->getOption('guidance_scale', 7.5),
            'seed'           => $this->config->getOption('seed', -1), // -1 for random
        ];

        // Add negative prompt if provided
        if ($request->negativePrompt) {
            $payload['negative_prompt'] = $request->negativePrompt;
        }

        // Add style preset
        if ($request->style !== 'natural') {
            $payload['style'] = $this->mapStyle($request->style);
        }

        // Add color guidance if colors are specified
        if (!empty($request->colors)) {
            $payload['color_guidance'] = [
                'enabled'  => true,
                'colors'   => $request->colors,
                'strength' => $this->config->getOption('color_strength', 0.5),
            ];
        }

        // Add quality settings
        $payload['quality'] = $this->config->getOption('quality', 'high');
        $payload['sampler'] = $this->config->getOption('sampler', 'dpmpp_2m');

        // Add member profile if provided
        if ($request->memberProfile) {
            $payload['user_id'] = $request->memberProfile;
        }

        $this->logger->info('Sending request to Seedream', [
            'endpoint' => $endpoint,
            'model'    => $model,
        ]);

        try {
            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            // Parse Seedream response
            $images       = [];
            $responseData = $response['body'];

            if (isset($responseData['images'])) {
                foreach ($responseData['images'] as $image) {
                    $images[] = [
                        'url'    => $image['url']    ?? null,
                        'base64' => $image['base64'] ?? null,
                        'seed'   => $image['seed']   ?? null,
                        'width'  => $image['width']  ?? (int)$width,
                        'height' => $image['height'] ?? (int)$height,
                    ];
                }
            }

            return ImageResponse::success(
                images: $images,
                metadata: [
                    'model'          => $model,
                    'steps'          => $payload['steps'],
                    'guidance_scale' => $payload['guidance_scale'],
                    'job_id'         => $responseData['job_id'] ?? null,
                ],
                jobId: $responseData['job_id'] ?? null
            );
        } catch (\Throwable $e) {
            $this->logger->error('Seedream generation failed', [
                'error' => $e->getMessage(),
            ]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    public function supports(string $feature): bool
    {
        return in_array($feature, config('ai_image_service.providers.seedream.supported_features'), true);
    }

    private function mapStyle(string $style): string
    {
        return match ($style) {
            'vivid'        => 'vibrant',
            'natural'      => 'realistic',
            'anime'        => 'anime',
            'digital-art'  => 'digital_art',
            'cinematic'    => 'cinematic',
            'photographic' => 'photographic',
            default        => 'realistic',
        };
    }
}
