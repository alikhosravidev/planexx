---
trigger: manual
---

# Enums

This document outlines the standards and best practices for using **enums** in the project. PHP 8.1 introduced native **enum** support, and we use them extensively to represent fixed sets of values.

## Declaring Cases

### Spacing

Ensure **no space** between cases, and they are all aligned under each other.

✅ **Correct:**

```php
enum ProductAccessType: int
{
    case Subscriber = 1;
    case Private = 2;
    case Organization = 3;
    case Interview = 4;
}
```

### Naming

Use **PascalCase** for **enum cases**. This makes them easily distinguishable from variables and methods.

✅ **Correct:**

```php
enum ProductAccessType: int
{
    case Subscriber = 1;
    case Private = 2;
}
```

## Helper Methods

### toEnglish()

Implement a `toEnglish()` method for **enums** that need to display **English text** for their values. This is useful for user-facing content.

```php
enum ProductAccessType: int
{
    case Subscriber = 1;
    case Private = 2;
    case Organization = 3;
    case Interview = 4;

    public function toEnglish(): string
    {
        return match($this) {
            self::Subscriber => 'Subscriber',
            self::Private => 'Private',
            self::Organization => 'Organization',
            self::Interview => 'Interview',
        };
    }
}
```

### EnglishList()

Implement a static `EnglishList()` method to return an array of all **enum cases** with their **English translations**. This is useful for generating dropdown lists or other UI elements.

```php
enum ProductAccessType: int
{
    case Subscriber = 1;
    case Private = 2;
    case Organization = 3;
    case Interview = 4;

    public function toEnglish(): string
    {
        return match($this) {
            self::Subscriber => 'Subscriber',
            self::Private => 'Private',
            self::Organization => 'Organization',
            self::Interview => 'Interview',
        };
    }

    public static function EnglishList(): array
    {
        return [
            self::Subscriber->value => self::Subscriber->toEnglish(),
            self::Private->value => self::Private->toEnglish(),
            self::Organization->value => self::Organization->toEnglish(),
            self::Interview->value => self::Interview->toEnglish(),
        ];
    }
}
```

## When to Use Enums

Use **enums** in the following scenarios:

1. When you have a **fixed set of related constants**
2. For status values, types, categories, or any other **classification**
3. When you need **type safety** for a specific set of values
4. When the values have associated **behavior or properties**

## Benefits of Using Enums

- **Type safety**: The compiler ensures only valid **enum values** are used
- **Self-documenting code**: **Enum cases** clearly communicate the possible values
- **Centralized definition**: All related constants are defined in one place
- **IDE support**: Autocompletion for **enum cases** and methods

## Examples

### Example 1: User Roles

```php
enum UserRole: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case Author = 'author';
    case Subscriber = 'subscriber';

    public function toEnglish(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Editor => 'Editor',
            self::Author => 'Author',
            self::Subscriber => 'Subscriber',
        };
    }

    public function canEditPosts(): bool
    {
        return match($this) {
            self::Admin, self::Editor, self::Author => true,
            self::Subscriber => false,
        };
    }
}
```

## Enum Validation in Laravel

When validating **enum values** in Laravel, use `Rule::enum()` instead of manual `Rule::in()` with **enum value** arrays. This provides better **type safety** and automatically stays in sync with **enum** changes.

**❌ Incorrect:**
```php
'type' => ['required', 'string', Rule::in([PlanType::Cash->value, PlanType::Installment->value])],
```

**✅ Correct:**
```php
'type' => [Rule::enum(PlanType::class)],
```

**Explanation:** Using `Rule::enum()` is more maintainable because it automatically reflects any changes to the **enum cases**. The manual array approach requires updating the validation rule whenever the **enum** is modified, which can lead to inconsistencies.

## Enum Discovery and Mapping

To make **enum APIs** dynamic and decoupled from hardcoded class references, we use an automated discovery and mapping mechanism:

1. A special command (`fetch enums`) scans the entire project source code using PHP reflection.
2. It finds all classes that are **enums** and builds a mapping of **enum names** to their fully qualified class names.
3. The discovered mapping is stored as a key-value array in `/var/www/planet/lsp/bootstrap/cache/enums_map.php`.
4. When an API request is made (e.g., `admin.enums.show?enum=StatusType`), the `EnumController` looks up the **enum name** in this cached map and dynamically resolves the correct **Enum class**.

This mechanism ensures that **enum APIs** are always up-to-date with the codebase, and new **enums** become available automatically after running the fetch command.

## Enum API Endpoints

To facilitate working with **enums** in the admin panel, we have three dedicated API endpoints, all defined as GET routes and returning data via the standardized `EnumTransformer`:

- **`admin.enums.index`**
  - **Purpose:** Retrieve data for multiple **enums** at once.
  - **Parameter:** `enum` (string) — Comma-separated list of **enum names**.
  - **Response:** A collection of **enum data** objects.

- **`admin.enums.show`**
  - **Purpose:** Retrieve data for a single **enum**.
  - **Parameter:** **Enum name** (string).
  - **Response:** **Enum data** object.

- **`admin.enums.keyValList`**
  - **Purpose:** Retrieve key-value pairs for a single **enum**, suitable for populating select inputs.
  - **Parameter:** **Enum name** (string).
  - **Response:** Key-value list of **enum options**.

All these routes are part of the admin API and return data using the `EnumTransformer` standard.

### Example Usage

```http title="Example: Fetch multiple enums"
GET /api/admin/enums/index?enum=StatusType,UserRole
```

```json title="Sample Response"
[
  {
    "name": "StatusType",
    "values": [ ... ]
  },
  {
    "name": "UserRole",
    "values": [
      {
        "value": 1,
        "english": "Admin",
        "persian": "مدیر"
      },
      {
        "value": 2,
        "english": "Editor",
        "persian": "ویرایشگر"
      },
      {
        "value": 3,
        "english": "Author",
        "persian": "نویسنده"
      },
      {
        "value": 4,
        "english": "Subscriber",
        "persian": "مشترک"
      }
    ]
  }

```
