<?php

declare(strict_types=1);

namespace App\Services\Transformer;

use App\Contracts\Transformer\DataExtractorInterface;
use App\Contracts\Transformer\SerializerInterface;
use App\Contracts\Transformer\TransformerInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

/**
 * Factory for creating transformer instances with proper dependency injection.
 */
readonly class TransformerFactory
{
    public function __construct(
        private Container             $container,
        private FractalManagerFactory $managerFactory,
        private TransformerConfig     $defaultConfig,
    ) {
    }

    /**
     * Create a transformer instance.
     *
     * @param string $transformerClass
     * @param TransformerConfig|null $config
     * @return TransformerInterface
     */
    public function make(string $transformerClass, ?TransformerConfig $config = null): TransformerInterface
    {
        $config ??= $this->defaultConfig;

        $registry = $this->container->make(FieldTransformerRegistry::class);

        foreach ($config->fieldTransformers as $field => $transformer) {
            $registry->register($field, $transformer);
        }

        $extractor = $this->container->make(DataExtractorInterface::class);
        $extractor->setIncludeAccessors($config->includeAccessors);

        $serializer = $this->container->make(SerializerInterface::class);

        $manager = $this->managerFactory->create(
            $serializer,
            $config->defaultIncludes,
        );

        $logger = $this->container->make(LoggerInterface::class);

        return new $transformerClass($config, $registry, $extractor, $manager, $logger);
    }

    /**
     * Create a transformer instance configured with request parameters.
     *
     * @param string $transformerClass
     * @param \Illuminate\Http\Request $request
     * @return TransformerInterface
     */
    public function makeFromRequest(string $transformerClass, Request $request): TransformerInterface
    {
        return $this->make($transformerClass)->withRequest($request);
    }
}
