# Migration Guide: From Old to New BaseTransformer

This guide helps migrate from the old magic-method based BaseTransformer to the new pipeline-based, type-safe implementation.

## Overview of Changes

### ❌ Removed Features
- Magic method resolution (`add*` methods)
- Request dependency in constructor
- Union types in public API
- Dynamic method calls with `Str::studly()`
- Hard-coded dependencies

### ✅ New Features
- Strict type safety
- Pipeline pattern with discrete steps
- Dependency injection
- Factory pattern for instantiation
- Explicit virtual field definitions
- Optional Request integration

## Migration Steps

### Step 1: Update Class Declaration

**Before:**

```php
<?php

namespace App\Transformers;

use App\Contracts\Transformer\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    protected array $additionalFields = ['full_name'];
    protected array $availableIncludes = ['posts'];
    protected array $defaultIncludes = [];
    protected bool $includeAccessors = true;
    protected array $fieldTransformers = [];

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
}
```

**After:**

```php
<?php

namespace App\Transformers;

use App\Contracts\Transformer\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    // No properties needed - everything configured via dependency injection
    // No constructor needed - dependencies auto-injected
}
```

### Step 2: Convert Magic Methods to Virtual Fields

**Before:**
```php
class UserTransformer extends BaseTransformer
{
    protected array $additionalFields = ['full_name', 'avatar_url', 'age'];

    protected function addFullName($model)
    {
        return $model->first_name . ' ' . $model->last_name;
    }

    protected function addAvatarUrl($model)
    {
        return Storage::url($model->avatar);
    }

    protected function addAge($model)
    {
        return Carbon::parse($model->birth_date)->age;
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
            'avatar_url' => fn($model) => Storage::url($model->avatar),
            'age' => fn($model) => Carbon::parse($model->birth_date)->age,
        ];
    }
}
```

### Step 3: Update Usage in Controllers

**Before:**
```php
public function show(User $user, Request $request)
{
    $transformer = new UserTransformer($request);
    return response()->json($transformer->transform($user));
}
```

**After:**
```php
public function show(User $user, Request $request)
{
    $transformer = app(\App\Services\Transformer\TransformerFactory::class)
        ->makeFromRequest(UserTransformer::class, $request);

    return response()->json($transformer->transformModel($user));
}
```

### Step 4: Update Fractal Resource Usage

**Before:**
```php
public function transformMany(Collection $users, Request $request)
{
    $transformer = new UserTransformer($request);
    $resource = new Collection($users, $transformer);
    return $this->manager->createData($resource)->toArray();
}
```

**After:**
```php
public function transformMany(Collection $users, Request $request)
{
    $transformer = app(\App\Services\Transformer\TransformerFactory::class)
        ->makeFromRequest(UserTransformer::class, $request);

    return $transformer->transformCollection($users);
}
```

### Step 5: Handle Custom Field Transformers

**Before:**
```php
class ProductTransformer extends BaseTransformer
{
    protected array $fieldTransformers = [
        'custom_price' => EuroTransformer::class,
    ];
}
```

**After:**

```php
class ProductTransformer extends BaseTransformer
{
    public function __construct(
        \App\Services\Transformer\TransformerConfig $config,
        \App\Services\Transformer\FieldTransformerRegistry $registry,
        \App\Contracts\Transformer\DataExtractorInterface $extractor,
        \League\Fractal\Manager $manager
    ) {
        parent::__construct($config, $registry, $extractor, $manager);

        // Register custom transformers
        $registry->register('custom_price', EuroTransformer::class);
    }
}
```

## Breaking Changes Reference

### 1. Constructor Changes
- **Before:** `public function __construct(Request $request = null)`
- **After:** No constructor needed (DI handled automatically)

### 2. Method Signature Changes
- **Before:** `public function transform($model): ?array`
- **After:** `public function transformModel(BaseModel $model): array`

### 3. Virtual Fields
- **Before:** Magic methods `addFullName()`, `$additionalFields` array
- **After:** Explicit `getVirtualFieldResolvers()` returning callable array

### 4. Request Integration
- **Before:** Passed to constructor
- **After:** Use `->withRequest($request)` or factory methods

### 5. Configuration
- **Before:** Protected properties on transformer class
- **After:** Configuration objects passed via DI

## Common Migration Issues

### Issue 1: Missing Virtual Fields
**Problem:** Virtual fields not appearing in output

**Solution:**
```php
// Wrong
protected function getVirtualFieldResolvers(): array
{
    return ['full_name' => 'addFullName']; // String, not callable
}

// Correct
protected function getVirtualFieldResolvers(): array
{
    return [
        'full_name' => fn($model) => $model->first_name . ' ' . $model->last_name
    ];
}
```

### Issue 2: Type Errors
**Problem:** `TypeError: transform() expects BaseModel, array given`

**Solution:**
```php
// Wrong
$transformer->transform($user);

// Correct
$transformer->transformModel($user);
```

### Issue 3: Request Parameters Not Working
**Problem:** Include/exclude parameters ignored

**Solution:**
```php
// Wrong
$transformer = new UserTransformer($request);

// Correct
$transformer = app(TransformerFactory::class)
    ->makeFromRequest(UserTransformer::class, $request);
```

## Testing Migration

### Before Migration Tests
```php
public function test_transforms_user()
{
    $user = factory(User::class)->create();
    $transformer = new UserTransformer();
    $result = $transformer->transform($user);

    $this->assertArrayHasKey('full_name', $result);
}
```

### After Migration Tests
```php
public function test_transforms_user()
{
    $user = User::factory()->create();
    $transformer = app(TransformerFactory::class)->make(UserTransformer::class);
    $result = $transformer->transformModel($user);

    $this->assertArrayHasKey('full_name', $result);
}
```

## Rollback Plan

If you need to rollback:

1. Keep old transformer classes with a different name
2. Update controllers to use old classes temporarily
3. Gradually migrate one transformer at a time
4. Test thoroughly in staging environment

## Benefits After Migration

- ✅ **Type Safety:** Strict typing prevents runtime errors
- ✅ **Testability:** Each component can be unit tested independently
- ✅ **Maintainability:** Clear separation of concerns
- ✅ **Performance:** No reflection or dynamic method calls
- ✅ **Flexibility:** Easy to customize pipeline steps
- ✅ **Consistency:** Uniform API across all transformers
