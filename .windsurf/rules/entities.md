---
trigger: manual
---

# Entities (Models)

Entities are **Eloquent models** that represent database tables and handle data persistence. Entities delegate complex operations to **repositories** for queries and **services** for business logic.

## Core Principles

### 1. Keep Entities Lean

**Critical Rule:** Entities must remain **lean** and focused solely on data representation.

**What Belongs in Entities:**
- Property definitions (`$fillable`, `$casts`, `$hidden`)
- **Relationships** (Eloquent methods)
- **Accessors and Mutators** (simple transformations)
- **Database-level events** (simple hooks)
- **Model-level contracts** (simple checks)

**What Doesn't Belong:**
- Complex business logic
- Complex queries
- External service calls
- Multi-model operations

**Example:**
```php
// ❌ Bad: Complex logic in entity
class Post extends Model
{
    public function publishWithNotifications()
    {
        $this->published_at = now();
        $this->save();
        $this->user->notify(new PostPublished($this));
        Cache::forget('posts');
    }
}

// ✅ Good: Delegate to service
class PostPublishService
{
    public function publish(Post $post): void
    {
        $post->published_at = now();
        $post->save();
        $this->notificationService->notifyPostPublished($post);
        $this->cacheService->invalidatePostsCache();
    }
}
```

---

### 2. Use `$fillable` Over `$guarded`

**Critical Rule:** Always use `$fillable` instead of `$guarded` for mass assignment protection.

**Why:**
- **Explicit whitelisting**: Clear which fields are mass-assignable
- **Security**: Prevents vulnerabilities when new columns are added
- **Maintainability**: New fields must be explicitly added

**Example:**
```php
// ❌ Bad: Using guarded
class User extends Model
{
    protected $guarded = ['id', 'is_admin'];
}

// ✅ Good: Using fillable
class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];
}
```

---

## What Belongs in Entities

### 1. Relationships

**Eloquent relationships** define model relations. Always use **type hints** and **descriptive names**.

```php
class Post extends Model
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps()
            ->withPivot('order');
    }
}
```

---

### 2. Accessors and Mutators

**Use for simple transformations only.** Avoid complex logic.

```php
class User extends Model
{
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }
}
```

---

### 3. Attribute Casting

**Casts** automatically convert attributes to data types.

```php
class Post extends Model
{
    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'rating' => 'decimal:2',
        'metadata' => 'array',
        'status' => PostStatus::class, // Enum casting
    ];
}
```

**Common types:** `boolean`, `integer`, `float`, `decimal:<precision>`, `array`, `json`, `datetime`, `encrypted`, Enum classes.

---

### 4. Database-Level Events

**Model events** hook into lifecycle moments: `creating`, `created`, `updating`, `updated`, `saving`, `saved`, `deleting`, `deleted`, `restoring`, `restored`.

**Use for simple hooks only.** Delegate complex logic to services.

---

### 5. Model-Level Contracts

**Use for simple attribute checks only.** Delegate complex validation to services.

```php
interface Publishable
{
    public function isPublished(): bool;
    public function isDraft(): bool;
}

class Post extends Model implements Publishable
{
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    public function isDraft(): bool
    {
        return $this->published_at === null;
    }
}
```

---

## What Doesn't Belong in Entities

**Delegate these to Services/Repositories:**

```php
// ❌ Bad: Complex business logic in entity
class Order extends Model
{
    public function processPayment(array $paymentData): bool { /* ... */ }
}

// ✅ Good: Use service
class OrderPaymentService
{
    public function processPayment(Order $order, array $paymentData): bool { /* ... */ }
}

// ❌ Bad: Complex queries in entity
class User extends Model
{
    public function getActiveSubscribersWithPendingOrders() { /* ... */ }
}

// ✅ Good: Use repository
class UserRepository
{
    public function getActiveSubscribersWithPendingOrders(): Collection { /* ... */ }
}

// ❌ Bad: External API calls in entity
class Product extends Model
{
    public function syncWithExternalInventory(): bool { /* ... */ }
}

// ✅ Good: Use service
class ProductSyncService
{
    public function syncStock(Product $product): bool { /* ... */ }
}
```

---

## Quick Reference

| Component | Belongs in Entity | Delegate to |
|-----------|-------------------|-------------|
| **Relationships** | ✅ Yes | - |
| **Accessors/Mutators** (simple) | ✅ Yes | - |
| **Attribute Casts** | ✅ Yes | - |
| **Database Events** (simple) | ✅ Yes | Service (complex) |
| **Model Contracts** (simple) | ✅ Yes | Service (complex) |
| **Complex Queries** | ❌ No | Repository |
| **Business Logic** | ❌ No | Service |
| **External APIs** | ❌ No | Service |
| **Multi-Model Operations** | ❌ No | Service |

## Summary

**Key Principles:**
1. **Keep entities lean** - Focus on data representation
2. **Use `$fillable` over `$guarded`** - For security
3. **Leverage Laravel features** - Relationships, casts, events
4. **Implement contracts** - For simple model behaviors
5. **Delegate complexity** - To services/repositories
