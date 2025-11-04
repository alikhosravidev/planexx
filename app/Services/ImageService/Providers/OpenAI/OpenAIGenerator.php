<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\OpenAI;

use App\Services\HttpClient;
use App\Services\ImageService\Contracts\ImageGeneratorInterface;
use App\Services\ImageService\DTOs\GenerateImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;
use App\Services\ImageService\DTOs\ProviderConfig;
use App\Services\Logger;

final class OpenAIGenerator implements ImageGeneratorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function generate(GenerateImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/images/generations';

        $payload = [
            'prompt' => $request->prompt,
            'n' => $request->numberOfImages,
            'size' => $request->size,
            'response_format' => 'url',
        ];

        // Add model if specified in config
        if ($model = $this->config->getOption('model')) {
            $payload['model'] = $model;
        }

        // Add quality if specified
        if ($quality = $this->config->getOption('quality')) {
            $payload['quality'] = $quality;
        }

        // Add style
        if ($request->style !== 'natural') {
            $payload['style'] = $request->style;
        }

        $this->logger->info('Sending request to OpenAI', ['endpoint' => $endpoint]);

        try {
            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type' => 'application/json',
            ]);

            $images = array_map(
                fn($img) => [
                    'url' => $img['url'] ?? null,
                    'revised_prompt' => $img['revised_prompt'] ?? null,
                ],
                $response['body']['data'] ?? []
            );

            return ImageResponse::success(
                images: $images,
                metadata: [
                    'created' => $response['body']['created'] ?? null,
                    'model' => $payload['model'] ?? 'dall-e-2',
                ]
            );
        } catch (\Throwable $e) {
            $this->logger->error('OpenAI generation failed', [
                'error' => $e->getMessage(),
            ]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    public function supports(string $feature): bool
    {
        $supportedFeatures = [
            'text_to_image',
            'high_quality',
            'style_control',
        ];

        return in_array($feature, $supportedFeatures, true);
    }
}
