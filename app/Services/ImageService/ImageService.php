<?php

declare(strict_types=1);

namespace App\Services\ImageService;

use App\Services\ImageService\Contracts\ImageProviderInterface;
use App\Services\ImageService\DTOs\EditImageRequest;
use App\Services\ImageService\DTOs\GenerateImageRequest;
use App\Services\ImageService\DTOs\ImageResponse;
use App\Services\ImageService\Exceptions\ProviderException;
use App\Services\Logger;

final class ImageService
{
    /** @var ImageProviderInterface[] */
    private array $providers = [];
    private readonly Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('ImageService');
    }

    /**
     * Register a provider
     */
    public function registerProvider(ImageProviderInterface $provider): self
    {
        $this->providers[$provider->getName()] = $provider;
        $this->logger->info("Provider registered: {$provider->getName()}");
        return $this;
    }

    /**
     * Generate image using the first available provider
     */
    public function generate(GenerateImageRequest $request, ?string $providerName = null): ImageResponse
    {
        $provider = $this->getProvider($providerName);
        
        $this->logger->info("Generating image with provider: {$provider->getName()}", [
            'prompt' => $request->prompt,
            'size' => $request->size,
        ]);

        try {
            $startTime = microtime(true);
            $response = $provider->getGenerator()->generate($request);
            $processingTime = microtime(true) - $startTime;

            return new ImageResponse(
                success: $response->success,
                images: $response->images,
                error: $response->error,
                metadata: array_merge($response->metadata, ['request' => $request->toArray()]),
                processingTime: $processingTime,
                provider: $provider->getName(),
                jobId: $response->jobId
            );
        } catch (\Throwable $e) {
            $this->logger->error("Image generation failed: {$e->getMessage()}", [
                'provider' => $provider->getName(),
                'exception' => get_class($e),
            ]);

            return ImageResponse::failure(
                error: $e->getMessage(),
                metadata: ['provider' => $provider->getName()],
                provider: $provider->getName()
            );
        }
    }

    /**
     * Edit image using the first available provider
     */
    public function edit(EditImageRequest $request, ?string $providerName = null): ImageResponse
    {
        $provider = $this->getProvider($providerName);

        $this->logger->info("Editing image with provider: {$provider->getName()}", [
            'operation' => $request->operation,
            'image_url' => $request->imageUrl,
        ]);

        try {
            $startTime = microtime(true);
            $response = $provider->getEditor()->edit($request);
            $processingTime = microtime(true) - $startTime;

            return new ImageResponse(
                success: $response->success,
                images: $response->images,
                error: $response->error,
                metadata: array_merge($response->metadata, ['request' => $request->toArray()]),
                processingTime: $processingTime,
                provider: $provider->getName(),
                jobId: $response->jobId
            );
        } catch (\Throwable $e) {
            $this->logger->error("Image editing failed: {$e->getMessage()}", [
                'provider' => $provider->getName(),
                'exception' => get_class($e),
            ]);

            return ImageResponse::failure(
                error: $e->getMessage(),
                metadata: ['provider' => $provider->getName()],
                provider: $provider->getName()
            );
        }
    }

    /**
     * Get available providers
     * @return ImageProviderInterface[]
     */
    public function getAvailableProviders(): array
    {
        return array_filter(
            $this->providers,
            fn(ImageProviderInterface $provider) => $provider->isAvailable()
        );
    }

    /**
     * Get specific provider or first available
     */
    private function getProvider(?string $name = null): ImageProviderInterface
    {
        if ($name !== null) {
            if (!isset($this->providers[$name])) {
                throw new ProviderException("Provider '{$name}' not found");
            }

            $provider = $this->providers[$name];
            if (!$provider->isAvailable()) {
                throw new ProviderException("Provider '{$name}' is not available");
            }

            return $provider;
        }

        $available = $this->getAvailableProviders();
        if (empty($available)) {
            throw new ProviderException('No available providers found');
        }

        return reset($available);
    }
}
