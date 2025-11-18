# Coding Standards

> **Source**: `.windsurf/rules/coding-standards.md`

## General Guidelines
- Follow **PSR-12** coding standards
- Use **meaningful** names
- **Single responsibility** functions
- Add comments for **complex logic only**
- **Consistent indentation** (spaces, not tabs)
- **120 characters** line length
- **Type hints** for parameters and returns
- **Unit tests** for all new functionality

## Naming Conventions
| Element | Convention | Example |
|---------|-----------|---------|
| Classes | PascalCase | `UserService` |
| Methods/Functions | camelCase | `getUserById` |
| Variables | camelCase | `$userCount` |
| Constants | UPPER_SNAKE_CASE | `MAX_LOGIN_ATTEMPTS` |
| Tables | snake_case, plural | `user_profiles` |
| Columns | snake_case | `first_name` |
| Enums | PascalCase | `ProductAccessType::Subscriber` |

## Code Organization
- **Thin controllers** - delegate to services
- **Repositories** for database interactions
- **Form Requests** for validation
- **Enums** for fixed values
- **Interfaces** for multiple implementations

## Best Practices

### Avoid Hard-coded Strings
```php
// ❌ Wrong
return $plan->title === 'پرداخت نقدی';

// ✅ Correct
return $plan->is_default;  // Use boolean flag
```

### Avoid Unnecessary Accessors
```php
// ❌ Wrong
public function getInstallmentCountAttribute($value): ?int {
    return $value === 0 ? null : $value;
}

// ✅ Correct
// Remove accessor, handle in view layer/DTOs
```

### Use Translation Files
```php
// ❌ Wrong
$title = 'پرداخت نقدی';

// ✅ Correct
$title = __('pricing-plans.cash_payment');
```

### Remove Unused Code
Regularly audit and remove unused classes, methods, files.

### Avoid Immediate Queries After Updates
```php
// ❌ Wrong
$this->repository->update($data, $id);
return $this->repository->findOrFail($id);

// ✅ Correct
return $this->repository->updateAndRefresh($data, $id);
```

## Code Reviews
1. Code owner review required
2. CTO review after code owner approval
3. Follow PR template
4. Address all review comments

## Full Details
`.windsurf/rules/coding-standards.md`

## Related
- Comments Policy: `.claude/guidelines/comments.md`
- Design Patterns: `.claude/patterns/design-patterns.md`
