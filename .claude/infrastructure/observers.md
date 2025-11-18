# Eloquent Observers

> **Source**: `.windsurf/rules/eloquent_observers.md`

## Purpose
Handle model lifecycle events cleanly without cluttering models or controllers.

## Key Benefits
- **Separation of Concerns**
- **DRY Principle**
- **Maintainability**
- **Testability**

## Available Events
- `creating`, `created`
- `updating`, `updated`
- `saving`, `saved`
- `deleting`, `deleted`
- `restoring`, `restored`
- `replicating`

## Basic Structure
```php
<?php

declare(strict_types=1);

namespace App\Core\Module\Observers;

use App\Core\Module\Entities\Enrollment;

class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        if ($this->hasProgressReachedCompletion($enrollment)) {
            event(new CourseCompleted($enrollment, $enrollment->user));
        }
    }

    private function hasProgressReachedCompletion(Enrollment $enrollment): bool
    {
        return $enrollment->isDirty('progress_percent')
            && ((int) $enrollment->progress_percent) === 100;
    }
}
```

## Registration

### In EventServiceProvider
```php
protected $observers = [
    Enrollment::class => EnrollmentObserver::class,
];
```

### Or Using Attribute (Laravel 11+)
```php
#[ObservedBy([EnrollmentObserver::class])]
class Enrollment extends Model
{
    // Model code
}
```

## Best Practices

### 1. Keep Observers Lightweight
Avoid heavy computations. Dispatch jobs/events for async tasks:
```php
// ❌ Wrong - Synchronous email
public function saved(Enrollment $enrollment): void
{
    Mail::to($enrollment->user->email)->send(new CourseCompletedEmail($enrollment));
}

// ✅ Correct - Dispatched job
public function saved(Enrollment $enrollment): void
{
    if ($this->hasProgressReachedCompletion($enrollment)) {
        SendCourseCompletedEmail::dispatch($enrollment);
    }
}
```

### 2. Prevent Infinite Loops
Use `withoutEvents()` when updating in observer:
```php
// ❌ Wrong - Causes loop
public function saved(Enrollment $enrollment): void
{
    $enrollment->update(['last_activity' => now()]);  // Triggers saved again!
}

// ✅ Correct
public function saved(Enrollment $enrollment): void
{
    $enrollment->withoutEvents(function () use ($enrollment) {
        $enrollment->update(['last_activity' => now()]);
    });
}
```

### 3. Document Observer Purpose
```php
/**
 * Handle the Enrollment saved event.
 * Dispatches CourseCompleted event when progress reaches 100%.
 * Used for triggering completion-related actions like certificates and notifications.
 */
public function saved(Enrollment $enrollment): void
{
    // Implementation
}
```

### 4. Test Independently
```php
class EnrollmentObserverTest extends TestCase
{
    public function test_dispatches_event_when_progress_reaches_100()
    {
        Event::fake();

        $enrollment = Enrollment::factory()->create(['progress_percent' => 99]);
        $enrollment->update(['progress_percent' => 100]);

        Event::assertDispatched(CourseCompleted::class);
    }
}
```

## Observer vs Boot Method

### Simple Logic → Use `booted()`
```php
protected static function booted(): void
{
    static::saved(function (Enrollment $enrollment) {
        if ($enrollment->isDirty('progress_percent') && $enrollment->progress_percent == 100) {
            event(new CourseCompleted($enrollment, $enrollment->user));
        }
    });
}
```

### Complex Logic → Use Observer Class
More organized, testable, and reusable.

## Common Patterns

### Auto-set Values
```php
public function creating(User $user): void
{
    if (!$user->uuid) {
        $user->uuid = Str::uuid();
    }
}
```

### Trigger Events
```php
public function deleted(Product $product): void
{
    event(new ProductDeleted($product));
}
```

### Update Related Models
```php
public function saved(Order $order): void
{
    $order->customer->withoutEvents(function () use ($order) {
        $order->customer->update(['last_order_at' => now()]);
    });
}
```

## Full Details
See: `.windsurf/rules/eloquent_observers.md`

## Related
- Entities: `.claude/domain/entities.md`
- Events: `.claude/events.md`
