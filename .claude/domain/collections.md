# Custom Collections

> **Source**: `.windsurf/rules/custom-collection.md`

## Why Use Custom Collections?
- **Type Safety** - Enforce specific object types
- **Readability** - Clear intent of contents
- **Built-in Methods** - Useful data manipulation
- **Consistency** - Unified style across project

Avoid using plain arrays unless required by framework.

## Implementation with BaseCollection

Custom collections extend `BaseCollection` and implement `setExpectedClass()`:

```php
abstract class BaseCollection
{
    protected string $expectedClass;

    protected function setExpectedClass(): void
    {
        // Implement in subclass
    }
}
```

## Example
```php
class PipelinePayloadCollection extends BaseCollection
{
    protected function setExpectedClass(): void
    {
        $this->expectedClass = PipelinePayload::class;
    }

    public function getByKey(float|int|string $key, mixed $default = null): null|string
    {
        return $this->where('key', $key)->first()?->value ?? $default;
    }
}
```

## Critical Rule: No Business Logic
Custom collections should **NEVER** contain business logic. They are solely for managing collections and providing type-safe access.

Business operations belong in the domain layer (Services, Entities).

## Best Practices
- **Immutability** - Return new instances for transformations
- **Minimal Interface** - Implement only necessary methods
- **Iterator Support** - Implement IteratorAggregate if needed
- **Type Safety** - Strictly enforce expected class

## When to Use
- Repeated collections of specific types
- Need for type-safe operations
- Custom filtering/searching methods
- Domain-specific collection behaviors

## Full Details
See: `.windsurf/rules/custom-collection.md`

## Related
- Value Objects: `.claude/domain/value-objects.md`
