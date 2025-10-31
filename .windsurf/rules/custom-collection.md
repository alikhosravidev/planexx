# Custom Collections in PHP

## Why Avoid Plain Arrays?

Arrays in PHP are flexible but lack type safety, which can lead to hidden bugs in large codebases.

## Why Use Custom Collections?

- **Type Safety:** Enforce that only specific types of objects are stored.
- **Readability:** Clear intent of what is contained.
- **Built-in Methods:** Provide useful methods for data manipulation.
- **Consistency:** Unified style across the project.

Avoid using arrays unless required by frameworks or external contracts.

## Implementation with BaseCustomCollection

Custom collections should extend `BaseCustomCollection` and implement the `setExpectedClass()` method to specify the expected class type.

```php
abstract class BaseCustomCollection
{
    protected string $expectedClass;

    protected function setExpectedClass(): void
    {
        // Implement in subclass
    }

    // Other base methods...
}
```

## Example: PipelinePayloadCollection

```php
class PipelinePayloadCollection extends BaseCustomCollection
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

## Key Rule: No Business Logic in Custom Collections

Custom collections should **never** contain business logic or commercial logic. They are solely for managing collections of data objects and providing type-safe access. Any business-related operations should be handled elsewhere in the domain layer.

## Best Practices

- **Immutability:** Return new instances for transformations.
- **Minimal Interface:** Implement only necessary methods.
- **Iterator Support:** Implement IteratorAggregate if needed.

## Summary

Custom collections enhance code quality by providing structure and type safety, but must remain free of business logic to maintain separation of concerns.
