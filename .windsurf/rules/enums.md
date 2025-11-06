---
trigger: manual
---

# Enums

This document defines standards and best practices for using **enums** in the project.

---

## Basic Structure

### Defining Cases

**Spacing:** No space between cases, aligned properly.

```php
enum UserTypeEnum: int
{
    case User     = 1;
    case Employee = 2;
    case Customer = 3;
}
```

### Naming

- **Enum:** `PascalCase` with `Enum` suffix
- **Cases:** `PascalCase`

---

## Required Methods

### 1. `label()` Method

For displaying **Persian** labels:

```php
public function label(): string
{
    return match ($this) {
        self::User     => 'کاربر',
        self::Employee => 'کارمند',
        self::Customer => 'مشتری',
    };
}
```

## Complete Pattern

```php
<?php

declare(strict_types=1);

namespace App\Core\User\Enums;

enum UserTypeEnum: int
{
    case User     = 1;
    case Employee = 2;
    case Customer = 3;

    public function label(): string
    {
        return match ($this) {
            self::User     => 'کاربر',
            self::Employee => 'کارمند',
            self::Customer => 'مشتری',
        };
    }
}
```

---

## Validation in Laravel

```php
// ❌ Incorrect
'type' => ['required', Rule::in([UserTypeEnum::User->value, ...])],

// ✅ Correct
'type' => ['required', Rule::enum(UserTypeEnum::class)],
```
---

## Enum Creation Checklist

- [ ] Name with `Enum` suffix
- [ ] Cases in `PascalCase`
- [ ] `label()` method for Persian labels
