---
trigger: manual
---

# Value Objects (VOs)

**Value Objects** represent **immutable** domain concepts by their **values**, enforcing **business rules** and maintaining **data integrity**.

## Introduction

Value Objects are immutable domain concepts defined by their values, not identity. They enforce business rules and ensure data integrity.

## Understanding Value Objects

- Represent data by **value**, not **identity**.
- Contain behavior relevant to their data.
- Always **immutable**.

**Vs Data Transfer Objects:** DTOs are data containers without behavior and can be mutable.

## Layer Placement

Value Objects belong in the **Domain Layer**. They should not appear in Application or Presentation layers. An Entity can have multiple Value Objects.

## Purpose and Benefits

Value Objects ensure:
- Consistent data type behavior system-wide.
- Uniform validations.
- Maintained structure and integrity.

## Core Characteristics

### Immutability

**Immutability is required** - Value Object state cannot change after creation.

- State cannot be modified; modifications return new instances.
- Guarantees valid state.

### Value Equality

Value Objects are compared by **attribute values**. Two VOs with same values are equal.

```php
new Email('user@example.com') == new Email('user@example.com'); // true
```

### Basic Structure

```php
class Email implements Stringable
{
    private function __construct(private readonly string $value)
    {
        $this->validate($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

### Self-Validation

Value Objects validate themselves upon creation, ensuring always valid state.

### Contextual Nature

Usage depends on context; same concept may be VO in one context, Entity in another.

## Implementation Guidelines

### Indicator of Design Quality

Presence of VOs indicates strong business rule implementation; absence may suggest anemic domain model.

### Gradual Improvement

Introduce VOs gradually to enrich domain model.

### When to Use Value Objects

Not every data type needs a VO. Use for repeated business rules and type identification.

Use VOs for:
- **Repeated Business Rules**: Consistent validation.
- **Domain Concepts**: Email, Phone, Money.
- **Value Composition**: Grouped related values.
- **Type Safety**: Prevent primitive obsession.
- **Business Rule Enforcement**: Mandatory rules.
- **Ubiquitous Language**: Reflect domain terminology.

### Benefits

- **Improved Clarity**: Express domain concepts.
- **Reduced Duplication**: Centralize validation.
- **Enhanced Safety**: Prevent misuse.
- **Easier Testing**: Self-contained units.

## Common Patterns

### Value Object Collections

```php
<?php
declare(strict_types=1);
abstract class TypedCollection extends Collection {
    public function __construct(array $elements = []) {
        Assert::allIsInstanceOf($elements, $this->type());
        parent::__construct($elements);
    }
    abstract protected function type(): string;
    public function add(mixed $element): void {
        Assert::isInstanceOf($element, $this->type());
        parent::add($element);
    }
}

final class EmailCollection extends TypedCollection {
    protected function type(): string {
        return Email::class;
    }
}
```

For more details on custom collections, see [Custom Collections](./custom-collection.md).

## Best Practices

1. **Keep Focused**: Single responsibility.
2. **Strict Immutability**: Return new instances.
3. **Comprehensive Validation**: Validate in constructor.
4. **Layer Awareness**: Stay in Domain layer.
5. **Laravel Integration**: Use casts, implement interfaces.
6. **Testing**: Test edge cases, immutability, equality.

## Example: Money Value Object

```php
class Money
{
    public function __construct(
        private readonly float $amount,
        private readonly string $currency
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->amount < 0) {
            throw new InvalidArgumentException('Negative amount');
        }
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Currency mismatch');
        }
        return new self($this->amount + $other->amount, $this->currency);
    }
}
```

## Conclusion

Value Objects create rich domain models by encapsulating validation and behavior. Identify primitives to convert into VOs for robustness.
