<?php

declare(strict_types=1);

namespace App\Services\AIImageService\Providers\Seedream;

use App\Services\AIImageService\Contracts\ImageEditorInterface;
use App\Services\AIImageService\DTOs\EditImageRequest;
use App\Services\AIImageService\DTOs\ImageResponse;
use App\Services\AIImageService\DTOs\ProviderConfig;
use App\Services\AIImageService\Exceptions\ProviderException;
use App\Services\HttpClient;
use Psr\Log\LoggerInterface;

final class SeedreamEditor implements ImageEditorInterface
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
                "Seedream does not support operation: {$request->operation}"
            );
        }

        return match ($request->operation) {
            'inpaint'   => $this->inpaint($request),
            'outpaint'  => $this->outpaint($request),
            'upscale'   => $this->upscale($request),
            'variation' => $this->createVariation($request),
            'enhance'   => $this->enhance($request),
            default     => throw new ProviderException("Unsupported operation: {$request->operation}")
        };
    }

    public function supportsOperation(string $operation): bool
    {
        return in_array($operation, config('ai_image_service.providers.seedream.supported_operations'), true);
    }

    private function inpaint(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/inpaint';

        try {
            $payload = [
                'model'          => $this->config->getOption('model', 'seedream-4'),
                'image_url'      => $request->imageUrl,
                'mask_url'       => $request->maskUrl,
                'prompt'         => $request->prompt ?? '',
                'steps'          => $this->config->getOption('steps', 50),
                'guidance_scale' => $this->config->getOption('guidance_scale', 7.5),
            ];

            if ($request->parameters['seed'] ?? null) {
                $payload['seed'] = $request->parameters['seed'];
            }

            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Seedream inpaint failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function outpaint(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/outpaint';

        try {
            $payload = [
                'model'           => $this->config->getOption('model', 'seedream-4'),
                'image_url'       => $request->imageUrl,
                'prompt'          => $request->prompt                        ?? 'extend the image naturally',
                'direction'       => $request->parameters['direction']       ?? 'all', // all, left, right, top, bottom
                'expansion_ratio' => $request->parameters['expansion_ratio'] ?? 1.5,
                'steps'           => $this->config->getOption('steps', 50),
            ];

            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Seedream outpaint failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function upscale(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/upscale';

        try {
            $payload = [
                'model'           => 'seedream-upscaler',
                'image_url'       => $request->imageUrl,
                'scale_factor'    => $request->parameters['scale_factor']    ?? 4, // 2x or 4x
                'enhance_details' => $request->parameters['enhance_details'] ?? true,
            ];

            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Seedream upscale failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function createVariation(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/variation';

        try {
            $payload = [
                'model'              => $this->config->getOption('model', 'seedream-4'),
                'image_url'          => $request->imageUrl,
                'num_variations'     => $request->parameters['num_variations']     ?? 1,
                'variation_strength' => $request->parameters['variation_strength'] ?? 0.7,
                'steps'              => $this->config->getOption('steps', 50),
            ];

            if ($request->prompt) {
                $payload['prompt'] = $request->prompt;
            }

            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Seedream variation failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function enhance(EditImageRequest $request): ImageResponse
    {
        $endpoint = $this->config->baseUrl . '/v1/enhance';

        try {
            $payload = [
                'model'            => 'seedream-enhancer',
                'image_url'        => $request->imageUrl,
                'enhancement_type' => $request->parameters['enhancement_type'] ?? 'auto', // auto, face, details, colors
                'strength'         => $request->parameters['strength']         ?? 0.8,
            ];

            $response = $this->httpClient->post($endpoint, $payload, [
                'Authorization' => 'Bearer ' . $this->config->apiKey,
                'Content-Type'  => 'application/json',
            ]);

            $images = $this->parseResponse($response);

            return ImageResponse::success(images: $images);
        } catch (\Throwable $e) {
            $this->logger->error('Seedream enhance failed', ['error' => $e->getMessage()]);

            return ImageResponse::failure($e->getMessage());
        }
    }

    private function parseResponse(array $response): array
    {
        $images       = [];
        $responseData = $response['body'];

        if (isset($responseData['images'])) {
            foreach ($responseData['images'] as $image) {
                $images[] = [
                    'url'    => $image['url']    ?? null,
                    'base64' => $image['base64'] ?? null,
                    'seed'   => $image['seed']   ?? null,
                    'width'  => $image['width']  ?? null,
                    'height' => $image['height'] ?? null,
                ];
            }
        }

        return $images;
    }
}
