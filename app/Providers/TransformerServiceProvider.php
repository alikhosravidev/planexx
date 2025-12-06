<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Transformer\DataExtractorInterface;
use App\Contracts\Transformer\SerializerInterface;
use App\Contracts\Transformer\TransformerInterface;
use App\Services\Transformer\ArraySerializerAdapter;
use App\Services\Transformer\CustomArraySerializer;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\FractalManagerFactory;
use App\Services\Transformer\ModelDataExtractor;
use App\Services\Transformer\TransformerConfig;
use App\Services\Transformer\TransformerFactory;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

/**
 * Service provider for transformer dependencies.
 */
class TransformerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind serializer
        $this->app->singleton(SerializerInterface::class, function ($app) {
            return new ArraySerializerAdapter(
                new CustomArraySerializer()
            );
        });

        // Bind extractor
        $this->app->bind(DataExtractorInterface::class, ModelDataExtractor::class);

        // Bind config
        $this->app->singleton(TransformerConfig::class, function () {
            return TransformerConfig::default();
        });

        // Bind factories
        $this->app->singleton(FractalManagerFactory::class, function ($app) {
            return new FractalManagerFactory($app);
        });

        $this->app->singleton(TransformerFactory::class);

        // Bind registry (per request)
        $this->app->singleton(FieldTransformerRegistry::class);

        // Bind Fractal Manager with CustomArraySerializer
        $this->app->bind(Manager::class, function ($app) {
            $manager = new Manager();
            $manager->setSerializer(new CustomArraySerializer());

            return $manager;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bind all BaseTransformer subclasses to use factory
        $this->app->resolving(TransformerInterface::class, function ($transformer, $app) {
            // Transformer is already constructed, but we need to ensure registry is populated
            $config   = $app->make(TransformerConfig::class);
            $registry = $app->make(FieldTransformerRegistry::class);

            foreach ($config->fieldTransformers as $field => $transformerClass) {
                $registry->register($field, $transformerClass);
            }
        });

        // Publish config if needed
        $this->publishes([
            __DIR__ . '/../../config/transformer.php' => config_path('transformer.php'),
        ], 'transformer-config');
    }
}
