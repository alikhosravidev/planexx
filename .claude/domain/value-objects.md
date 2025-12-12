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
Value Objects belong in the **Domain Layer**.
- **Shared VOs**: `app/ValueObjects/` (e.g., Email, Mobile)
- **Module-specific VOs**: Within module domain (e.g., `Services/Auth/ValueObjects/Identifier`)

## Example from Codebase
```php
// Value Object Example: Identifier
final class Identifier implements Stringable
{
    public readonly IdentifierType $type;
    public readonly string $value;

    public function __construct(
        string $identifier,
        private readonly AuthConfig $authConfig,
    ) {
        $this->type  = $this->detectType($identifier);
        $this->value = $this->normalize($identifier);
    }

    public function equals(self $other): bool {
        return $this->type === $other->type && $this->value === $other->value;
    }
}

// Simple Value Object: Email
final readonly class Email
{
    public function __construct(public string $value) {
        if (!preg_match(CustomValidator::EMAIL_REGEX, $this->value)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
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
