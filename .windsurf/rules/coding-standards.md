---
trigger: manual
---

# Coding Standards

This document outlines **coding standards** for **clean**, **readable**, and **consistent code** across the codebase.

## General Guidelines

- Follow **PSR-12** coding standards for PHP code
- Use **meaningful** variable and function names
- Keep functions **small** and focused on a **single responsibility**
- Add **appropriate comments** to explain **complex logic**
- Use **consistent indentation** (spaces, not tabs)
- Limit line length to **120 characters**
- Use **type hints** for parameters and return types
- Write **unit tests** for all new functionality

## Code Organization

The project follows a **standard Laravel structure** with additional conventions:

- **Controllers** should be **thin** and delegate business logic to **services**
- Use **repositories** for database interactions
- Place validation logic in dedicated **Form Request classes**
- Use **enums** for fixed sets of values
- Implement **interfaces** for services with multiple implementations

## Naming Conventions

- **Classes**: PascalCase (e.g., `UserService`)
- **Methods/Functions**: camelCase (e.g., `getUserById`)
- **Variables**: camelCase (e.g., `$userCount`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_LOGIN_ATTEMPTS`)
- **Database Tables**: snake_case, plural (e.g., `user_profiles`)
- **Database Columns**: snake_case (e.g., `first_name`)
- **Enums**: PascalCase for enum name, PascalCase for cases (e.g., `ProductAccessType::Subscriber`)

## File Structure

- **One class per file**
- Filename should match the **class name**
- Group **related files** in appropriate **directories**
- Follow **Laravel's convention** for file locations

## Code Reviews

All code must undergo review before merging:

1. Each **merge request** must be reviewed by the **code owner**
2. If not approved by code owner, it won't be reviewed by **CTO**
3. Follow the established **pull request template**
4. Address all **review comments** before re-requesting review

## Documentation

- **Document** all **public methods** and functions
- Update the **README** when adding new commands or features
- Include **examples** for **complex functionality**
- **Document** any **non-obvious behavior** or edge cases

## Business Logic Best Practices

### Avoid Hard-coded String Comparisons

Avoid **hard-coded string comparisons** for business logic, as they create **brittle code** prone to errors. Use **boolean flags** or **enum values** instead.

**❌ Incorrect:**
```php
public function getDefaultCashPlan(): ?PricingPlan
{
    return $this->first(function (PricingPlan $plan) {
        return $plan->type === PlanType::Cash
            && $plan->is_active
            && $plan->title === 'پرداخت نقدی'; // Hard-coded string comparison
    });
}
```

**✅ Correct:**
```php
public function getDefaultCashPlan(): ?PricingPlan
{
    return $this->first(function (PricingPlan $plan) {
        return $plan->type === PlanType::Cash
            && $plan->is_active
            && $plan->is_default; // Use boolean flag
    });
}
```

**Explanation:** Using **hard-coded strings** for decision-making logic makes the code **fragile** and hard to maintain. When the string value needs to change, it requires updating multiple places in the codebase. **Boolean flags** or **enum values** provide better type safety and make the intent clearer.

### Avoid Unnecessary Accessors in Models

Do not create **accessors** (getters) in Eloquent models that simply convert null or zero values to other representations. This adds unnecessary complexity and reduces readability. Handle value transformations in the **view layer** or **DTOs** instead.

**❌ Incorrect:**
```php
public function getInstallmentCountAttribute($value): ?int
{
    return $value === 0 ? null : $value;
}

public function getDownPaymentAttribute($value): ?int
{
    return $value === 0 ? null : $value;
}
```

**✅ Correct:**
Remove these accessors entirely. If the database stores 0 and you need to display it differently in the UI, handle this transformation in the view layer or when creating DTOs.

**Explanation:** **Accessors** should be used for meaningful data transformations, not for converting zero values to null. This keeps models focused on their core responsibilities and improves code clarity. Value formatting should be handled closer to the presentation layer.

### Use Translation Files for User-Facing Text

Avoid hardcoding **user-facing strings** in code. Instead, use Laravel's **translation files** to make text **localizable** and **maintainable**.

**❌ Incorrect:**
```php
$dto = new PricingPlanDTO(
    product: $product,
    title: 'پرداخت نقدی',
    // ...
);
```

**✅ Correct:**
```php
$dto = new PricingPlanDTO(
    product: $product,
    title: __('pricing-plans.cash_payment'),
    // ...
);
```

**Explanation:** Hardcoding text strings in code makes localization difficult and scatters text throughout the codebase. Using **translation files** centralizes all user-facing text, making it easier to maintain and translate.

### Remove Unused Classes and Code

Regularly audit the codebase to identify and remove **classes**, **methods**, and **files** that are no longer used. This keeps the codebase **clean** and reduces **maintenance overhead**.

**❌ Incorrect:**
Keeping classes like `PricingPlanCollection` that have no references in the codebase.

**✅ Correct:**
Remove the unused class entirely if it's not being used anywhere.

**Explanation:** **Unused code** increases the cognitive load on developers and can lead to confusion. Regular cleanup ensures the codebase remains focused and maintainable.

### Avoid Immediate Database Queries After Updates

Instead of immediately fetching updated models from the database after performing an update, implement a **refresh mechanism** within the **repository pattern** to reload model data efficiently.

**❌ Incorrect:**
```php
$this->repository->update($dto->toArray(), $plan->id);
return $this->repository->findOrFail($plan->id); // Immediate query
```

**✅ Correct:**
```php
$updatedPlan = $this->repository->updateAndRefresh($dto->toArray(), $plan->id);
return $updatedPlan; // Use repository's refresh method
```

**Explanation:** Performing immediate database queries after updates can lead to unnecessary round trips and performance issues. A well-designed **repository** should handle refreshing model data as part of its update operations.

Following these standards will help maintain a **high-quality codebase** and make collaboration easier for all team members.
