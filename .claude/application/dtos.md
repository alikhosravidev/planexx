# Data Transfer Objects (DTOs)

> **Source**: `.windsurf/rules/data-transfer-objects.md`

## What are DTOs?
Simple objects that carry data between processes, creating clear boundaries between system layers.

**DTOs vs Value Objects:**
- DTOs = Pure data containers, no behavior (except `toArray`), can be mutable
- VOs = Immutable domain concepts with validation and behavior

## Core Principles

### 1. Constructor Requirement
Every DTO **MUST** have a constructor:
```php
// ✅ Correct
class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email = null
    ) {}
}
```

### 2. Mandatory Properties
At least one property must be mandatory (no default):
```php
// ❌ Wrong - All optional
class ArticleDTO {
    public function __construct(
        public ?string $title = null,
        public ?string $content = null
    ) {}
}

// ✅ Correct
class ArticleDTO {
    public function __construct(
        public string $title,  // Mandatory
        public ?string $content = null
    ) {}
}
```

### 3. No Business Logic
DTOs must NOT contain:
- Business logic requiring external dependencies
- Static factory methods (`fromArray`, `create`, etc.)
- Any methods except `toArray` (if implementing `Arrayable`)

```php
// ❌ Wrong
class OrderDTO {
    public static function fromArray(array $data): self { }  // Not allowed!
    public function applyDiscount(): float { }  // Not allowed!
}

// ✅ Correct
class OrderDTO implements Arrayable {
    public function __construct(
        public readonly float $amount
    ) {}

    public function toArray(): array {
        return ['amount' => $this->amount];
    }
}
```

### 4. Use Public Readonly Properties
```php
// ✅ Preferred
class ProductDTO {
    public function __construct(
        public readonly string $name,
        public readonly float $price
    ) {}
}

// ❌ Avoid getters/setters
class ProductDTO {
    private string $name;
    public function getName(): string { return $this->name; }
}
```

### 5. Strong Typing
```php
// ❌ Wrong
public function __construct(float|string $totalPrice) { }

// ✅ Correct
public function __construct(public readonly float $totalPrice) { }
```

## Validation

### Basic Validation
Include basic validation in constructor:
```php
class EmailDTO {
    public function __construct(string $address) {
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }
        $this->address = $address;
    }
}
```

### Complex Validation
Delegate to validation services:
```php
final class PricingPlanDTO implements Arrayable
{
    public function __construct(...) {
        PricingPlanValidationService::validateDto($this);
    }
}
```

## Constructor Parameter Organization
```php
// ✅ Recommended - Required first, then optional, grouped logically
class ProductDTO {
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?string $description = null,
        public readonly ?string $category = null
    ) {}
}
```

## Summary Checklist
- [ ] Has constructor with at least one mandatory property
- [ ] Uses `public readonly` properties
- [ ] No business logic (only `toArray` if `Arrayable`)
- [ ] No static factory methods
- [ ] Strong typing, no union types
- [ ] Required parameters first

## Full Details
See: `.windsurf/rules/data-transfer-objects.md`

## Related
- Value Objects: `.claude/domain/value-objects.md`
- Mappers: `.claude/application/mappers.md`
