# Transformers

## Architecture Overview
Pipeline-based transformation system built on League Fractal with strict type safety and dependency injection.

## Pipeline Steps
1. **DataExtractionStep** - Extract data from model (`attributesToArray()` + `relationsToArray()`)
2. **BlacklistFilterStep** - Remove blacklisted fields (recursive on nested data)
3. **FieldTransformationStep** - Apply field transformers (recursive on nested data)
4. **VirtualFieldResolutionStep** - Resolve virtual/computed fields

## Creating Transformers

### Basic Transformer
```php
class UserTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['posts', 'department'];

    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'full_name' => fn($user) => $user->first_name . ' ' . $user->last_name,
        ];
    }
}
```

### With Relationships (Includes)
```php
class DepartmentTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['parent', 'manager', 'children'];

    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,
    ];

    public function includeParent(Department $department)
    {
        return $this->item($department->parent, $this);
    }

    public function includeManager(Department $department)
    {
        return $this->item($department->manager, resolve(UserTransformer::class));
    }

    // Recursive includes for nested relations
    public function includeChildren(Department $department)
    {
        $children = $department->children;

        if ($children->isEmpty()) {
            return $this->null();
        }

        $childTransformer = resolve(self::class);
        $childTransformer->setDefaultIncludes(['children']);

        return $this->collection($children, $childTransformer);
    }
}
```

## Available Methods
- `transformModel(EntityInterface $model): array` - Single model
- `transformCollection(Collection $models): array` - Collection
- `transformArray(array $data): array` - Array data
- `transformOne($model, ?string $resourceKey): array` - With Fractal resource
- `transformMany($model, ?string $resourceKey): array` - Collection with Fractal

## Field Transformers
Register in transformer class:
```php
protected array $fieldTransformers = [
    'type'        => EnumTransformer::class,
    'created_at'  => DateTimeTransformer::class,
    'price'       => PriceTransformer::class,
    'description' => LongTextTransformer::class,
];
```

### Built-in Transformers
| Transformer | Input | Output |
|-------------|-------|--------|
| `DateTimeTransformer` | Carbon/string | `{main, default, human: {jalali, gregorian}}` |
| `EnumTransformer` | BackedEnum | `{name, value, label, cases, ...extraMethods}` |
| `PriceTransformer` | int/float | `{raw, formatted, currency}` |
| `LongTextTransformer` | string | `{raw, summary, word_count, char_count, read_time, html}` |
| `DurationTransformer` | int (seconds) | `{seconds, formatted, human}` |

## Includes & Excludes

### Via Request Query Params
```
GET /api/users?includes=posts,department&excludes=password
```

### Manual Configuration
```php
$transformer = app(UserTransformer::class)
    ->setIncludes(['posts', 'department'])
    ->setExcludes(['password'])
    ->setDefaultIncludes(['profile']);
```

## Recursive Transformation
Field transformers and blacklist filters are applied recursively to nested data:
- **Nested Collections** - Arrays like `children: [{...}, {...}]`
- **Nested Associative Arrays** - Objects like `parent: {...}`

This ensures consistent transformation across all nesting levels.

## Virtual Fields
Computed fields not stored in database:
```php
protected function getVirtualFieldResolvers(): array
{
    return [
        'full_name'  => fn($user) => $user->first_name . ' ' . $user->last_name,
        'avatar_url' => fn($user) => Storage::url($user->avatar),
        'is_admin'   => fn($user) => $user->hasRole('admin'),
    ];
}
```

## Key Points
- ✅ Use `getVirtualFieldResolvers()` for computed fields
- ✅ Use `fieldTransformers` array for field-specific transformations
- ✅ Use `setDefaultIncludes()` for recursive nested relations
- ✅ Transformations are recursive on nested data
- ❌ No magic `add*` methods
- ❌ Don't use `transform()` directly, use typed methods
