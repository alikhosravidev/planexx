<?php

declare(strict_types=1);

namespace App\Services\ImageService\Providers\OpenAI;

use App\Services\HttpClient;
use App\Services\ImageService\Contracts\ImageEditorInterface;
use App\Services\ImageService\DTOs\EditImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;
use App\Services\ImageService\DTOs\ProviderConfig;
use App\Services\ImageService\Exceptions\ProviderException;
use App\Services\Logger;

final class OpenAIEditor implements ImageEditorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly Logger $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function edit(EditImageRequest $request): ImageResponse
    {
        // OpenAI supports only specific operations
        if (!$this->supportsOperation($request->operation)) {
            throw new ProviderException(
                "OpenAI does not support operation: {$request->operation}"
            );
        }

        return match ($request->operation) {
            'inpaint' => $this->inpaint($request),
            'variation' => $this->createVariation($request),
            default => throw new ProviderException("Unsupported operation: {$request->operation}")
        };
    }

    public function supportsOperation(string $operation): bool
    {
        return in_array($operation, ['inpaint', 'variation'], true);
    }

    private function inpaint(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/images/edits';

        try {
            // Download image and mask to temp files
            $imagePath = $this->downloadToTemp($request->imageUrl);
            $maskPath = $this->downloadToTemp($request->maskUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'prompt' => $request->prompt ?? '',
                    'n' => 1,
                    'size' => $request->parameters['size'] ?? '1024x1024',
                ],
                [
                    'image' => $imagePath,
                    'mask' => $maskPath,
                ],
                [
                    'Authorization' => 'Bearer ' . $this->config->apiKey,
                ]
            );

            // Clean up temp files
            @unlink($imagePath);
            @unlink($maskPath);

            $images = array_map(
                fn($img) => ['url' => $img['url'] ?? null],
                $response['body']['data'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('OpenAI inpaint failed', ['error' => $e->getMessage()]);
            return ImageResponse::failure($e->getMessage());
        }
    }

    private function createVariation(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/images/variations';

        try {
            $imagePath = $this->downloadToTemp($request->imageUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'n' => $request->parameters['n'] ?? 1,
                    'size' => $request->parameters['size'] ?? '1024x1024',
                ],
                [
                    'image' => $imagePath,
                ],
                [
                    'Authorization' => 'Bearer ' . $this->config->apiKey,
                ]
            );

            @unlink($imagePath);

            $images = array_map(
                fn($img) => ['url' => $img['url'] ?? null],
                $response['body']['data'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('OpenAI variation failed', ['error' => $e->getMessage()]);
            return ImageResponse::failure($e->getMessage());
        }
    }

    private function downloadToTemp(string $url): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'img_');
        $content = file_get_contents($url);
        
        if ($content === false) {
            throw new ProviderException("Failed to download image from: {$url}");
        }

        file_put_contents($tempFile, $content);
        return $tempFile;
    }
}
