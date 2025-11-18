# Transformers

> **Sources**: `docs/transformer-usage.md`, `docs/migration-guide.md`

## Modern Pipeline-Based Transformers
Strict type safety, dependency injection, explicit virtual fields.

## Basic Usage

### Creating Transformers
```php
class UserTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'full_name' => fn($user) => $user->first_name . ' ' . $user->last_name,
            'avatar_url' => fn($user) => Storage::url($user->avatar),
        ];
    }
}
```

### Using Transformers (Recommended)
```php
// Via Factory
$transformer = app(TransformerFactory::class)
    ->makeFromRequest(UserTransformer::class, $request);

$result = $transformer->transformModel($user);      // Single
$result = $transformer->transformCollection($users); // Collection
$result = $transformer->transformArray($data);       // Array
```

## Available Methods
- `transformModel(BaseModel $model): array`
- `transformArray(array $data): array`
- `transformCollection(Collection $models): array`

## Configuration

### Default Field Transformers
Pre-configured:
- `created_at`, `updated_at` → `DateTimeTransformer`
- `price`, `amount` → `PriceTransformer`
- `description`, `text` → `LongTextTransformer`

### Request Integration
```php
// Include/Excludes from query params
$transformer = $factory->makeFromRequest(UserTransformer::class, $request);

// Manual
$transformer = app(UserTransformer::class)
    ->setIncludes(['posts'])
    ->setExcludes(['password'])
    ->transformModel($user);
```

## Migration from Old System

### Before
```php
protected array $additionalFields = ['full_name'];

protected function addFullName($model) {
    return $model->first_name . ' ' . $model->last_name;
}
```

### After
```php
protected function getVirtualFieldResolvers(): array {
    return [
        'full_name' => fn($model) => $model->first_name . ' ' . $model->last_name,
    ];
}
```

## Key Changes
- ❌ No magic `add*` methods
- ✅ Use `getVirtualFieldResolvers()`
- ✅ Use `TransformerFactory` for instantiation
- ✅ Strict types: `transformModel()`, not `transform()`

## Full Details
- Usage: `docs/transformer-usage.md`
- Migration: `docs/migration-guide.md`
