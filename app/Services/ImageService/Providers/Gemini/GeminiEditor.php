<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\Gemini;

use App\Services\HttpClient;
use App\Services\ImageService\Contracts\ImageEditorInterface;
use App\Services\ImageService\DTOs\EditImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;
use App\Services\ImageService\DTOs\ProviderConfig;
use App\Services\ImageService\Exceptions\ProviderException;
use App\Services\Logger;

final class GeminiEditor implements ImageEditorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function edit(EditImageRequest $request): ImageResponse
    {
        if (!$this->supportsOperation($request->operation)) {
            throw new ProviderException(
                "Gemini does not support operation: {$request->operation}"
            );
        }

        return match ($request->operation) {
            'enhance' => $this->enhance($request),
            'style_transfer' => $this->styleTransfer($request),
            'variation' => $this->createVariation($request),
            default => throw new ProviderException("Unsupported operation: {$request->operation}")
        };
    }

    public function supportsOperation(string $operation): bool
    {
        return in_array($operation, ['enhance', 'style_transfer', 'variation'], true);
    }

    private function enhance(EditImageRequest $request): ImageResponse
    {
        $model = $this->config->getOption('model', 'gemini-2.5-flash');
        $endpoint = $this->config->baseUrl . "/v1/models/{$model}:generateContent";

        try {
            $imageData = $this->getImageBase64($request->imageUrl);
            
            $prompt = $request->prompt ?? 'Enhance this image, improve quality and details';

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $imageData,
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 8192,
                ],
            ];

            $response = $this->httpClient->post(
                $endpoint . '?key=' . $this->config->apiKey,
                $payload,
                ['Content-Type' => 'application/json']
            );

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Gemini enhance failed', ['error' => $e->getMessage()]);
            return ImageResponse::failure($e->getMessage());
        }
    }

    private function styleTransfer(EditImageRequest $request): ImageResponse
    {
        $model = $this->config->getOption('model', 'gemini-2.5-flash');
        $endpoint = $this->config->baseUrl . "/v1/models/{$model}:generateContent";

        try {
            $imageData = $this->getImageBase64($request->imageUrl);
            
            $style = $request->parameters['style'] ?? 'artistic';
            $prompt = $request->prompt ?? "Apply {$style} style to this image";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $imageData,
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 8192,
                ],
            ];

            $response = $this->httpClient->post(
                $endpoint . '?key=' . $this->config->apiKey,
                $payload,
                ['Content-Type' => 'application/json']
            );

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Gemini style transfer failed', ['error' => $e->getMessage()]);
            return ImageResponse::failure($e->getMessage());
        }
    }

    private function createVariation(EditImageRequest $request): ImageResponse
    {
        $model = $this->config->getOption('model', 'gemini-2.5-flash');
        $endpoint = $this->config->baseUrl . "/v1/models/{$model}:generateContent";

        try {
            $imageData = $this->getImageBase64($request->imageUrl);
            
            $prompt = $request->prompt ?? 'Create a variation of this image while maintaining its core elements';

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $imageData,
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.9,
                    'maxOutputTokens' => 8192,
                ],
            ];

            $response = $this->httpClient->post(
                $endpoint . '?key=' . $this->config->apiKey,
                $payload,
                ['Content-Type' => 'application/json']
            );

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Gemini variation failed', ['error' => $e->getMessage()]);
            return ImageResponse::failure($e->getMessage());
        }
    }

    private function getImageBase64(string $url): string
    {
        $content = file_get_contents($url);
        
        if ($content === false) {
            throw new ProviderException("Failed to download image from: {$url}");
        }

        return base64_encode($content);
    }

    private function parseResponse(array $response): array
    {
        $candidates = $response['body']['candidates'] ?? [];
        $images = [];

        foreach ($candidates as $candidate) {
            $content = $candidate['content'] ?? [];
            $parts = $content['parts'] ?? [];
            
            foreach ($parts as $part) {
                if (isset($part['text'])) {
                    $images[] = [
                        'type' => 'text_description',
                        'description' => $part['text'],
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

        return $images;
    }
}
