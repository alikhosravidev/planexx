<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\StabilityAI;

use App\Services\AIImageService\Contracts\ImageGeneratorInterface;
use App\Services\AIImageService\DTOs\GenerateImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;
use App\Services\AIImageService\DTOs\ProviderConfig;
use App\Services\HttpClient;
use Psr\Log\LoggerInterface;

final class StabilityAIGenerator implements ImageGeneratorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly LoggerInterface $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function generate(GenerateImageRequest $request): ImageResponse
    {
        $engine   = $this->config->getOption('engine', 'stable-diffusion-xl-1024-v1-0');
        $endpoint = $this->config->baseUrl . "/v1/generation/{$engine}/text-to-image";

        // Parse size
        [$width, $height] = explode('x', $request->size);

        $payload = [
            'text_prompts' => [
                [
                    'text'   => $request->prompt,
                    'weight' => 1,
                ],
            ],
            'cfg_scale' => $this->config->getOption('cfg_scale', 7),
            'height'    => (int)$height,
            'width'     => (int)$width,
            'samples'   => $request->numberOfImages,
            'steps'     => $this->config->getOption('steps', 30),
        ];

        // Add negative prompt if provided
        if ($request->negativePrompt) {
            $payload['text_prompts'][] = [
                'text'   => $request->negativePrompt,
                'weight' => -1,
            ];
        }

        // Add style preset
        if ($request->style !== 'natural') {
            $payload['style_preset'] = $this->mapStyle($request->style);
        }

        $this->logger->info('Sending request to Stability AI', ['endpoint' => $endpoint]);

        try {
            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = array_map(
                fn ($artifact) => [
                    'url'           => null, // Stability returns base64, you may want to save it
                    'base64'        => $artifact['base64']       ?? null,
                    'seed'          => $artifact['seed']         ?? null,
                    'finish_reason' => $artifact['finishReason'] ?? null,
                ],
                $response['body']['artifacts'] ?? []
            );

            return ImageResponse::success(
                images: $images,
                metadata: [
                    'engine' => $engine,
                    'steps'  => $payload['steps'],
                ]
            );
        } catch (\Throwable $e) {
            $this->logger->error('Stability AI generation failed', [
                'error' => $e->getMessage(),
            ]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    public function supports(string $feature): bool
    {
        return in_array($feature, config('ai_image_service.providers.stability_ai.supported_features'), true);
    }

    private function mapStyle(string $style): string
    {
        return match ($style) {
            'vivid'       => 'enhance',
            'natural'     => 'photographic',
            'anime'       => 'anime',
            'digital-art' => 'digital-art',
            default       => 'photographic',
        };
    }
}
