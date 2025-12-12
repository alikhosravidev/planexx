# Enums

> **Source**: `.windsurf/rules/enums.md`

## Naming Conventions
- **Enum Name**: `PascalCase` with `Enum` suffix
- **Cases**: `PascalCase`, aligned properly

```php
enum UserTypeEnum: int
{
    case User     = 1;
    case Employee = 2;
    case Customer = 3;
}
```

## Required Method: `label()`
Every enum MUST have a `label()` method for **Persian** display:

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

## Complete Pattern from Codebase
```php
<?php

declare(strict_types=1);

namespace App\Core\Organization\Enums;

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

    // Additional helper methods (optional)
    public function plural(): string { /* ... */ }
    public function icon(): string { /* ... */ }
}
```

## Validation in Laravel
```php
// ❌ Incorrect
'type' => ['required', Rule::in([UserTypeEnum::User->value, ...])],

// ✅ Correct
'type' => ['required', Rule::enum(UserTypeEnum::class)],
```

## Enum Creation Checklist
- [ ] Name with `Enum` suffix
- [ ] Cases in `PascalCase`, properly aligned
- [ ] `label()` method for Persian labels
- [ ] Use `Rule::enum()` for validation

## Full Details
See: `.windsurf/rules/enums.md`
