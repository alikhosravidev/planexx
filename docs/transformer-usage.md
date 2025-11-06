# Transformer Usage Guide

This guide explains how to use the refactored transformer system with strict type safety, dependency injection, and pipeline pattern.

## Installation

### 1. Register Service Provider

Add the service provider to `config/app.php`:

```php
'providers' => [
    // ... other providers
    App\Providers\TransformerServiceProvider::class,
],
```

### 2. Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="App\Providers\TransformerServiceProvider" --tag="transformer-config"
```

## Basic Usage

### Creating Transformers

Extend `BaseTransformer` and implement virtual fields:

```php
<?php

namespace App\Transformers;

use App\Contracts\Transformer\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'full_name' => fn(User $user) => $user->first_name . ' ' . $user->last_name,
            'avatar_url' => fn(User $user) => Storage::url($user->avatar),
            'is_active' => fn(User $user) => $user->status === 'active',
        ];
    }
}
```

### Using Transformers

#### Method 1: Factory Pattern (Recommended)

```php
use App\Services\Transformer\TransformerFactory;

$factory = app(TransformerFactory::class);

// Simple usage
$transformer = $factory->make(UserTransformer::class);
$result = $transformer->transformModel($user);

// With custom config
$config = TransformerConfig::default()->merge(new TransformerConfig(
    blacklistedFields: ['password', 'secret']
));
$transformer = $factory->make(UserTransformer::class, $config);

// With request parameters
$transformer = $factory->makeFromRequest(UserTransformer::class, $request);
$result = $transformer->transformModel($user);
```

#### Method 2: Direct Instantiation

```php
$transformer = new UserTransformer(
    app(TransformerConfig::class),
    app(FieldTransformerRegistry::class),
    app(DataExtractorInterface::class),
    app(Manager::class)
);

$result = $transformer->transformModel($user);
```

#### Method 3: Fluent API with Request

```php
$result = app(UserTransformer::class)
    ->withRequest($request)
    ->setIncludes(['posts', 'comments'])
    ->transformModel($user);
```

## Available Methods

The `TransformerInterface` provides strict typing:

```php
interface TransformerInterface
{
    public function transformModel(BaseModel $model): array;
    public function transformArray(array $data): array;
    public function transformCollection(Collection $models): array;
}
```

## Configuration

### Default Field Transformers

The system comes with pre-configured field transformers:

- `created_at`, `updated_at`, `deleted_at` → `DateTimeTransformer`
- `description`, `text`, `body` → `LongTextTransformer`
- `price`, `amount` → `PriceTransformer`
- And more...

### Custom Field Transformers

Register custom transformers in your transformer:

```php
class ProductTransformer extends BaseTransformer
{
    public function __construct(
        TransformerConfig $config,
        FieldTransformerRegistry $registry,
        DataExtractorInterface $extractor,
        Manager $manager
    ) {
        parent::__construct($config, $registry, $extractor, $manager);

        // Register custom field transformer
        $registry->register('custom_field', CustomTransformer::class);
    }
}
```

### Virtual Fields

Virtual fields are computed properties that don't exist on the model:

```php
class OrderTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'total_with_tax' => fn(Order $order) =>
                $order->subtotal + ($order->subtotal * $order->tax_rate),

            'formatted_total' => fn(Order $order) =>
                '$' . number_format($order->total, 2),

            'is_overdue' => fn(Order $order) =>
                $order->due_date->isPast() && !$order->is_paid,
        ];
    }
}
```

## Request Integration

### Include/Excludes

```php
// URL: /api/users?includes=posts,comments&excludes=created_at,updated_at

$transformer = $factory->makeFromRequest(UserTransformer::class, $request);
$result = $transformer->transformModel($user);
```

### Manual Configuration

```php
$transformer = app(UserTransformer::class)
    ->setIncludes(['posts', 'comments'])
    ->setExcludes(['password'])
    ->transformModel($user);
```

## Advanced Usage

### Custom Pipeline Steps

You can override pipeline creation to add custom steps:

```php
class CustomTransformer extends BaseTransformer
{
    protected function buildPipeline(): TransformationPipeline
    {
        return new TransformationPipeline([
            $this->createDataExtractionStep(),
            $this->createBlacklistFilterStep(),
            new CustomValidationStep(),
            new CustomTransformationStep(),
            $this->createVirtualFieldResolutionStep(),
        ]);
    }
}
```

### Custom Configuration

```php
$config = new TransformerConfig(
    fieldTransformers: [
        'price' => EuroTransformer::class,
        'date' => GermanDateTransformer::class,
    ],
    blacklistedFields: ['internal_id', 'secret_key'],
    availableIncludes: ['items', 'categories'],
    defaultIncludes: ['items'],
    includeAccessors: false,
);

$transformer = $factory->make(ProductTransformer::class, $config);
```

## Testing

### Unit Tests

```php
class UserTransformerTest extends TestCase
{
    public function test_transforms_user_with_virtual_fields(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $transformer = app(TransformerFactory::class)->make(UserTransformer::class);
        $result = $transformer->transformModel($user);

        $this->assertEquals('John Doe', $result['full_name']);
    }
}
```

### Mocking Dependencies

```php
public function test_transforms_with_mocked_dependencies(): void
{
    $config = Mockery::mock(TransformerConfig::class);
    $registry = Mockery::mock(FieldTransformerRegistry::class);
    $extractor = Mockery::mock(DataExtractorInterface::class);
    $manager = Mockery::mock(Manager::class);

    $transformer = new UserTransformer($config, $registry, $extractor, $manager);
    $result = $transformer->transformModel($user);

    // Assertions...
}
```

## Migration Guide

### From Old BaseTransformer

**Before:**
```php
class UserTransformer extends BaseTransformer
{
    protected array $additionalFields = ['full_name'];

    protected function addFullName($model)
    {
        return $model->first_name . ' ' . $model->last_name;
    }

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
}
```

**After:**
```php
class UserTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'full_name' => fn($model) => $model->first_name . ' ' . $model->last_name,
        ];
    }

    // No constructor needed - dependencies injected automatically
}
```

### Breaking Changes

1. **Request dependency removed** - Use `withRequest()` or factory methods
2. **Magic methods eliminated** - Use explicit `getVirtualFieldResolvers()`
3. **Union types replaced** - Use specific `transformModel()`, `transformArray()`, `transformCollection()`
4. **No more `Str::studly()`** - Virtual fields explicitly defined

## Performance Notes

- Transformers are lightweight and can be reused
- Pipeline pattern allows for step-level caching if needed
- Virtual field resolvers are lazy-loaded
- Fractal Manager handles complex include/exclude logic efficiently
