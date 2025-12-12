# Entities (Eloquent Models)

> **Source**: `.windsurf/rules/entities.md`

## Core Principle
**Entities must remain LEAN** - focused solely on data representation.

## What Belongs in Entities

### ✅ Allowed
- Property definitions (`$fillable`, `$casts`, `$hidden`)
- Relationships (Eloquent methods)
- Accessors/Mutators (simple transformations only)
- Database-level events (simple hooks)
- Model-level contracts (simple checks)

### ❌ Not Allowed
- Complex business logic → **Use Services**
- Complex queries → **Use Repositories**
- External service calls → **Use Services**
- Multi-model operations → **Use Services**

## Critical Rules

### 1. Always Use `$fillable`, Never `$guarded`
```php
// ❌ Wrong
class User extends Model {
    protected $guarded = ['id'];
}

// ✅ Correct
class User extends Model {
    protected $fillable = ['name', 'email', 'password'];
}
```

**Why**: Explicit whitelisting prevents security vulnerabilities.

### 2. Type-safe Relationships
```php
public function author(): BelongsTo {
    return $this->belongsTo(User::class, 'user_id');
}

public function comments(): HasMany {
    return $this->hasMany(Comment::class);
}
```

### 3. Attribute Casting
```php
protected $casts = [
    'published_at' => 'datetime',
    'is_featured' => 'boolean',
    'metadata' => 'array',
    'status' => PostStatus::class,  // Enum casting
];
```

### 4. Simple Accessors Only
```php
// ✅ Allowed - Simple transformation
protected function fullName(): Attribute {
    return Attribute::make(
        get: fn () => "{$this->first_name} {$this->last_name}"
    );
}

// ❌ Not Allowed - Complex logic
protected function statusBadge(): Attribute {
    return Attribute::make(
        get: fn () => $this->calculateComplexStatus()  // Too complex!
    );
}
```

## Docblock Standards

Keep docblocks concise and synced with migrations:
```php
/**
 * @property int                         $id
 * @property string                      $name
 * @property string|null                 $email
 * @property UserTypeEnum                $user_type
 * @property GenderEnum|null             $gender
 * @property bool                        $is_active
 *
 * Relations:
 * @property HasMany                     $addresses
 * @property BelongsTo                   $directManager
 */
class User extends BaseEntity { }
```

**Note**: Use `BaseEntity` instead of `Model` for consistency with the codebase.

## Quick Reference

| Component | Belongs in Entity | Delegate to |
|-----------|-------------------|-------------|
| Relationships | ✅ | - |
| Accessors (simple) | ✅ | - |
| Attribute Casts | ✅ | - |
| Database Events (simple) | ✅ | Service (complex) |
| Model Contracts (simple) | ✅ | Service (complex) |
| Complex Queries | ❌ | Repository |
| Business Logic | ❌ | Service |
| External APIs | ❌ | Service |

## Full Details
See: `.windsurf/rules/entities.md`
