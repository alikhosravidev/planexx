<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\Gemini;

use App\Services\HttpClient;
use App\Services\ImageService\Contracts\ImageGeneratorInterface;
use App\Services\ImageService\DTOs\GenerateImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;
use App\Services\ImageService\DTOs\ProviderConfig;
use App\Services\Logger;

final class GeminiGenerator implements ImageGeneratorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function generate(GenerateImageRequest $request): ImageResponse
    {
        $model = $this->config->getOption('model', 'gemini-2.5-flash');
        $endpoint = $this->config->baseUrl . "/v1/models/{$model}:generateContent";

        // Build the prompt with image generation instructions
        $fullPrompt = "Generate an image: {$request->prompt}";
        
        if ($request->negativePrompt) {
            $fullPrompt .= "\n\nAvoid: {$request->negativePrompt}";
        }

        if (!empty($request->colors)) {
            $fullPrompt .= "\n\nColors: " . implode(', ', $request->colors);
        }

        if ($request->style !== 'natural') {
            $fullPrompt .= "\n\nStyle: {$request->style}";
        }

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $fullPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $this->config->getOption('temperature', 0.9),
                'topK' => $this->config->getOption('top_k', 40),
                'topP' => $this->config->getOption('top_p', 0.95),
                'maxOutputTokens' => $this->config->getOption('max_tokens', 8192),
            ],
        ];

        // Add safety settings if configured
        if ($safetySettings = $this->config->getOption('safety_settings')) {
            $payload['safetySettings'] = $safetySettings;
        }

        $this->logger->info('Sending request to Gemini', [
            'endpoint' => $endpoint,
            'model' => $model,
        ]);

        try {
            $response = $this->httpClient->post(
                $endpoint . '?key=' . $this->config->apiKey,
                $payload,
                ['Content-Type' => 'application/json']
            );

            // Parse Gemini response
            $candidates = $response['body']['candidates'] ?? [];
            $images = [];

            foreach ($candidates as $candidate) {
                $content = $candidate['content'] ?? [];
                $parts = $content['parts'] ?? [];
                
                foreach ($parts as $part) {
                    // Gemini may return text descriptions or image data
                    if (isset($part['text'])) {
                        $images[] = [
                            'type' => 'text_description',
                            'description' => $part['text'],
                            'metadata' => [
                                'finish_reason' => $candidate['finishReason'] ?? null,
                                'safety_ratings' => $candidate['safetyRatings'] ?? [],
                            ],
                        ];
                    }
                    
                    if (isset($part['inlineData'])) {
                        $images[] = [
                            'type' => 'image',
                            'mime_type' => $part['inlineData']['mimeType'] ?? 'image/png',
                            'base64' => $part['inlineData']['data'] ?? null,
                        ];
                    }
                }
            }

            return ImageResponse::success(
                images: $images,
                metadata: [
                    'model' => $model,
                    'usage' => $response['body']['usageMetadata'] ?? null,
                ]
            );
        } catch (\Throwable $e) {
            $this->logger->error('Gemini generation failed', [
                'error' => $e->getMessage(),
            ]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    public function supports(string $feature): bool
    {
        $supportedFeatures = [
            'text_to_image',
            'multimodal',
            'high_quality',
            'style_control',
            'color_control',
        ];

        return in_array($feature, $supportedFeatures, true);
    }
}
