---
trigger: manual
---

## Introduction

**Data Transfer Objects (DTOs)** are simple objects that carry data between processes, creating clear boundaries between system layers and ensuring data integrity. This guide outlines team standards for implementing DTOs.

**Note:** DTOs are distinct from higher-level concepts like Data Structures. Focus on DTOs as pure data carriers.

## Core Principles

### 1. Constructor Requirement

Every DTO **must** have a constructor to ensure data integrity.

**❌ Incorrect:**
```php
class UserDTO
{
    public string $name;
    public ?string $email;
}
```

**✅ Correct:**
```php
class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $email = null
    ) {}
}
```

### 2. Mandatory Properties

Every DTO must have at least one mandatory property in its constructor.

**❌ Incorrect:**
```php
class ArticleDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $content = null
    ) {}
}
```

**✅ Correct:**
```php
class ArticleDTO
{
    public function __construct(
        public string $title,
        public ?string $content = null
    ) {
    }
}
```

### 3. No Business Logic or Additional Behaviors

DTOs should not contain any business logic or additional behaviors. The only allowed method is **toArray** if the DTO implements the **Arrayable** interface.

**Business Logic** requires external dependencies or changes system state.

**Construction Logic** was previously allowed, but now prohibited. No static methods like **fromArray**, **create**, or any other factory methods are permitted.

**❌ Incorrect:**
```php
class OrderDTO
{
    public function __construct(float $amount) {
        $this->amount = $amount;
    }

    public function applyDiscount(DiscountService $discountService): float
    {
        $discount = $discountService->getDiscountFor($user);
        return $this->amount * (1 - $discount);
    }
}
```

**✅ Correct:**
```php
class OrderDTO
{
    public function __construct(public readonly float $amount) {}
}
```

**Golden Rule:** DTOs must contain no behaviors except optionally **toArray** when implementing **Arrayable**. If a method needs anything beyond input parameters and internal state, it's prohibited.

### 4. Constructor Parameter Organization

Organize parameters: required first (no defaults), then optional (with defaults). Group related parameters.

**✅ Recommended:**
```php
class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?string $description = null
    ) {}
}
```

### 5. No Getters and Setters

Avoid getters/setters. Use **public readonly properties**.

**❌ Unnecessary:**
```php
class ProductDTO
{
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }
}
```

**✅ Better:**
```php
class ProductDTO
{
    public function __construct(public readonly string $name) {}
}
```

## Property Types

**Recommended:** **Public readonly properties** for immutability.

Use private properties only if necessary, but prefer public readonly.

### Strong Typing

Enforce strict types to prevent invalid inputs.

**❌ Incorrect:**
```php
public function __construct(float|string $totalPrice) {
    $this->totalPrice = is_numeric($totalPrice) ? (float) $totalPrice : 0;
}
```

**✅ Correct:**
```php
public function __construct(public readonly float $totalPrice) {}
```

## Validation

Include basic validation in constructor for data integrity. Use validation services for complex rules.

**Example:**
```php
class EmailDTO
{
    public function __construct(string $address) {
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }
        $this->address = $address;
    }
}
```

**Advanced:** Delegate to validation service.

**❌ Incorrect:** Complex validation in constructor.
```php
class PricingPlanDTO
{
    public function __construct(...) {
        if ($totalPrice < 0) throw new Exception('...');
        // more checks
    }
}
```

**✅ Correct:**
```php
final class PricingPlanDTO implements Arrayable
{
    public function __construct(...) {
        PricingPlanValidationService::validateDto($this);
    }

    public function toArray(): array {
        return [
            'title' => $this->title,
            'total_price' => $this->totalPrice,
            'is_active' => $this->isActive,
        ];
    }
}
```

### Allowed Methods in DTOs

DTOs may only contain the **toArray** method if they implement the **Arrayable** interface. No other methods, including static factory methods like **fromArray** or **create**, are allowed.

**✅ Correct:**
```php
final class ProductDTO implements Arrayable
{
    public function __construct(
        public readonly string $name,
        public readonly float $price
    ) {}

    public function toArray(): array {
        return ['name' => $this->name, 'price' => $this->price];
    }
}
```

**❌ Incorrect:**
```php
class ProductDTO
{
    public static function fromEntity(Product $product): self {
        return new self($product->getName(), $product->getPrice());
    }
}
```

### Business Logic Examples (Prohibited)

**❌ Incorrect:** External dependencies, database access, state changes.

```php
class OrderDTO
{
    public function calculateFinalPrice(TaxService $taxService): float {
        $taxRate = $taxService->getTaxRateForOrder($this);
        return $this->amount * (1 + $taxRate);
    }
}
```

## Real-World Example

**Basic DTO:**
```php
class AddressDTO
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $zipCode,
        public readonly string $country
    ) {}
}
```

**Advanced DTO:**
```php
class UserRegistrationDTO
{
    public function __construct(
        string $username,
        string $email,
        string $password
    ) {
        if (strlen($username) < 3) {
            throw new InvalidArgumentException('...');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('...');
        }
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('...');
        }

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}
```

## Summary

**Key Guidelines:**
1. Every DTO must have a constructor.
2. At least one mandatory property.
3. No business logic or additional behaviors; only **toArray** if implementing **Arrayable**.
4. Use public readonly properties.
5. Strong typing and validation.
6. Follow parameter order.
7. No static methods like **fromArray** or **create**.

**Golden Rule:** If a method needs external resources, it's prohibited.
