<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\StabilityAI;

use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\DTOs\EditImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;
use App\Services\AIImageService\DTOs\ProviderConfig;
use App\Services\AIImageService\Exceptions\ProviderException;
use App\Services\HttpClient;
use Psr\Log\LoggerInterface;

final class StabilityAIEditor implements ImageEditorInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly LoggerInterface $logger,
        private readonly ProviderConfig $config
    ) {
    }

    public function edit(EditImageRequest $request): ImageResponse
    {
        if (!$this->supportsOperation($request->operation)) {
            throw new ProviderException(
                "Stability AI does not support operation: {$request->operation}"
            );
        }

        return match ($request->operation) {
            'inpaint'   => $this->inpaint($request),
            'outpaint'  => $this->outpaint($request),
            'upscale'   => $this->upscale($request),
            'variation' => $this->createVariation($request),
            default     => throw new ProviderException("Unsupported operation: {$request->operation}")
        };
    }

    public function supportsOperation(string $operation): bool
    {
        return in_array($operation, config('ai_image_service.providers.stability_ai.supported_operations'), true);
    }

    private function inpaint(EditImageRequest $request): ImageResponse
    {
        $engine   = $this->config->getOption('engine', 'stable-diffusion-xl-1024-v1-0');
        $endpoint = $this->config->baseUrl . "/v1/generation/{$engine}/image-to-image/masking";

        try {
            $imagePath = $this->downloadToTemp($request->imageUrl);
            $maskPath  = $this->downloadToTemp($request->maskUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'text_prompts[0][text]'   => $request->prompt ?? '',
                    'text_prompts[0][weight]' => '1',
                    'cfg_scale'               => (string)$this->config->getOption('cfg_scale', 7),
                    'samples'                 => '1',
                ],
                [
                    'init_image' => $imagePath,
                    'mask_image' => $maskPath,
                ],
                [
                    'Authorization' => 'Bearer ' . $this->config->apiKey,
                ]
            );

            @unlink($imagePath);
            @unlink($maskPath);

            $images = array_map(
                fn ($artifact) => [
                    'base64' => $artifact['base64'] ?? null,
                    'seed'   => $artifact['seed']   ?? null,
                ],
                $response['body']['artifacts'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Stability AI inpaint failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function outpaint(EditImageRequest $request): ImageResponse
    {
        $engine   = $this->config->getOption('engine', 'stable-diffusion-xl-1024-v1-0');
        $endpoint = $this->config->baseUrl . "/v1/generation/{$engine}/image-to-image";

        try {
            $imagePath = $this->downloadToTemp($request->imageUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'text_prompts[0][text]'   => $request->prompt ?? 'extend the image',
                    'text_prompts[0][weight]' => '1',
                    'cfg_scale'               => (string)$this->config->getOption('cfg_scale', 7),
                    'samples'                 => '1',
                ],
                [
                    'init_image' => $imagePath,
                ],
                [
                    'Authorization' => 'Bearer ' . $this->config->apiKey,
                ]
            );

            @unlink($imagePath);

            $images = array_map(
                fn ($artifact) => ['base64' => $artifact['base64'] ?? null],
                $response['body']['artifacts'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Stability AI outpaint failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function createVariation(EditImageRequest $request): ImageResponse
    {
        $engine   = $this->config->getOption('engine', 'stable-diffusion-xl-1024-v1-0');
        $endpoint = $this->config->baseUrl . "/v1/generation/{$engine}/image-to-image";

        try {
            $imagePath = $this->downloadToTemp($request->imageUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'text_prompts[0][text]'   => $request->prompt ?? 'variation of the image',
                    'text_prompts[0][weight]' => '1',
                ],
                [
                    'init_image' => $imagePath,
                ],
                [
                    'Authorization' => 'Bearer ' . $this->config->apiKey,
                ]
            );

            @unlink($imagePath);

            $images = array_map(
                fn ($artifact) => ['base64' => $artifact['base64'] ?? null],
                $response['body']['artifacts'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Stability AI createVariation failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function upscale(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/generation/esrgan-v1-x2plus/image-to-image/upscale';

        try {
            $imagePath = $this->downloadToTemp($request->imageUrl);

            $response = $this->httpClient->postMultipart(
                $endpoint,
                [
                    'width' => (string)($request->parameters['width'] ?? 2048),
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
                fn ($artifact) => ['base64' => $artifact['base64'] ?? null],
                $response['body']['artifacts'] ?? []
            );

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Stability AI upscale failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function downloadToTemp(string $url): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'img_');
        $content  = file_get_contents($url);

        if ($content === false) {
            throw new ProviderException("Failed to download image from: {$url}");
        }

        file_put_contents($tempFile, $content);

        return $tempFile;
    }
}
