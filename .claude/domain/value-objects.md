# Value Objects (VOs)

> **Source**: `.windsurf/rules/value-objects.md`

## What are Value Objects?
**Immutable** domain concepts representing data by **value**, not **identity**.

## Core Characteristics

### 1. Immutability (Required)
State cannot change after creation. Modifications return new instances.

### 2. Self-Validation
VOs validate themselves upon creation, ensuring always-valid state.

### 3. Value Equality
Compared by attribute values, not identity:
```php
new Email('user@example.com') == new Email('user@example.com'); // true
```

## Basic Structure
```php
class Email implements Stringable
{
    private function __construct(private readonly string $value) {
        $this->validate($value);
    }

    public static function fromString(string $value): self {
        return new self($value);
    }

    private function validate(string $value): void {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }
    }

    public function equals(self $other): bool {
        return $this->value === $other->value;
    }

    public function __toString(): string {
        return $this->value;
    }
}
```

## When to Use Value Objects

Use for:
- **Repeated Business Rules** - Consistent validation
- **Domain Concepts** - Email, Phone, Money
- **Value Composition** - Grouped related values
- **Type Safety** - Prevent primitive obsession
- **Business Rule Enforcement** - Mandatory rules

Don't use for:
- Simple types without business rules
- Data that doesn't need validation
- Temporary calculations

## Layer Placement
Value Objects belong in the **Domain Layer**. Shared VOs are in `app/Bus/ValueObjects/`.

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

    private function validate(): void {
        if ($this->amount < 0) {
            throw new InvalidArgumentException('Negative amount');
        }
    }

    public function add(Money $other): self {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Currency mismatch');
        }
        return new self(
            $this->amount + $other->amount,
            $this->currency
        );
    }
}
```

## Benefits
- **Improved Clarity** - Express domain concepts
- **Reduced Duplication** - Centralize validation
- **Enhanced Safety** - Prevent misuse
- **Easier Testing** - Self-contained units

## Best Practices
1. Keep focused on single responsibility
2. Strict immutability - return new instances
3. Comprehensive validation in constructor
4. Stay in Domain layer
5. Implement `Stringable` when appropriate
6. Test edge cases, immutability, equality

## Full Details
See: `.windsurf/rules/value-objects.md`

## Related
- Custom Collections: `.claude/domain/collections.md`
- DTOs vs VOs: `.claude/application/dtos.md`
