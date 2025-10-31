---
trigger: manual
---

### **Principle:**
Use **Eloquent Observers** to Handle Model Events Cleanly

**Explanation:**
**Eloquent Observers** allow listening to and reacting to model lifecycle events without cluttering models or controllers. This promotes **separation of concerns**, improves maintainability, and follows the **DRY principle**. Observers handle events like creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored, and replicating. Register in service providers or use #[ObservedBy] in Laravel 11+. Avoid placing business logic in models or controllers; use observers for side effects like sending emails, logging, or triggering events.

**Example (Wrong 👎):**
```php
// Placing event logic directly in the controller, violating separation of concerns
class EnrollmentController extends Controller
{
    public function updateProgress(UpdateProgressRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        // Bad: Checking progress and firing event here
        if ($enrollment->progress_percent == 100) {
            event(new CourseCompleted($enrollment, $enrollment->user));
        }
    }
}
```

**Example (Correct 👍):**
```php
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

class LMSEventsServiceProvider extends EventServiceProvider
{
    protected $observers = [
        Enrollment::class => [
            EnrollmentObserver::class,
        ],
    ];
}
```

### **Principle:**
Register **Observers** Properly for Automatic Event Handling

---

**Explanation:**
**Observers** must be registered with models to function. Use the `observe()` method in a service provider or the `#[ObservedBy]` attribute on the model. This ensures automatic calling during events. Avoid manual registration in controllers; centralize in service providers.

**Example (Wrong 👎):**
```php
// Manual observer registration in controller, leading to scattered code
class EnrollmentController extends Controller
{
    public function __construct()
    {
        Enrollment::observe(EnrollmentObserver::class);
    }
}
```

**Example (Correct 👍):**
```php
class LMSEventsServiceProvider extends EventServiceProvider
{
    protected $observers = [
        Enrollment::class => EnrollmentObserver::class,
    ];
}

// Or using attribute (Laravel 11+)
#[ObservedBy([EnrollmentObserver::class])]
class Enrollment extends Model
{
    // Model code
}
```

### **Principle:**
Avoid Complex Logic in **Observers**; Delegate to Jobs or Events

---

**Explanation:**
Keep **observers** lightweight by avoiding heavy computations or API calls directly. Dispatch **jobs** or **events** for asynchronous tasks. This improves performance and prevents blocking. Use queues for emails or processing.

**Example (Wrong 👎):**
```php
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        // Bad: Sending email synchronously in observer
        Mail::to($enrollment->user->email)->send(new CourseCompletedEmail($enrollment));
    }
}
```

**Example (Correct 👍):**
```php
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        if ($this->hasProgressReachedCompletion($enrollment)) {
            SendCourseCompletedEmail::dispatch($enrollment);
        }
    }
}
```

### **Principle:**
Test **Observers** Independently for Reliability

---

**Explanation:**
Unit test **observers** in isolation for correct reactions to events. Test scenarios and edge cases without full integration. This ensures reliability and safer refactoring.

**Example (Wrong 👎):**
```php
// No tests for observer, leading to untested side effects
```

**Example (Correct 👍):**
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

### **Principle:**
Choose Between **Observer Classes** and **Boot Method** Based on Complexity

---

**Explanation:**
For simple logic, use the model's `booted()` method. For complex or reusable logic, use a dedicated **observer class**. Classes improve organization, testability, and reusability; boot method is concise for small apps.

**Example (Wrong 👎):**
```php
class Enrollment extends Model
{
    protected static function booted(): void
    {
        static::saved(function (Enrollment $enrollment) {
            // Bad: Complex logic in boot method
            if ($enrollment->isDirty('progress_percent') && $enrollment->progress_percent == 100) {
                Mail::to($enrollment->user->email)->send(new CourseCompletedEmail($enrollment));
                Log::info('Course completed for user: ' . $enrollment->user->id);
                // More complex logic...
            }
        });
    }
}
```

**Example (Correct 👍):**
```php
class Enrollment extends Model
{
    protected static function booted(): void
    {
        static::saved(function (Enrollment $enrollment) {
            // Simple logic only
            if ($enrollment->isDirty('progress_percent') && $enrollment->progress_percent == 100) {
                event(new CourseCompleted($enrollment, $enrollment->user));
            }
        });
    }
}

// Complex logic in observer
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        if ($this->hasProgressReachedCompletion($enrollment)) {
            SendCourseCompletedEmail::dispatch($enrollment);
            Log::info('Course completed for user: ' . $enrollment->user->id);
        }
    }
}
```

### **Principle:**
Prevent Infinite Loops in **Observers**

---

**Explanation:**
Updating a model inside its **observer** can cause recursive triggers. Use `withoutEvents()` or check for changes before updating.

**Example (Wrong 👎):**
```php
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        // Bad: Updating the same model triggers saved event again
        $enrollment->update(['last_activity' => now()]);
    }
}
```

**Example (Correct 👍):**
```php
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        // Prevent loop
        $enrollment->withoutEvents(function () use ($enrollment) {
            $enrollment->update(['last_activity' => now()]);
        });
    }
}
```

### **Principle:**
Document **Observer** Purposes and Behaviors

---

**Explanation:**
**Observers**' effects may not be obvious. Document purposes, handled events, and side effects in comments or docblocks. This aids maintainability.

**Example (Wrong 👎):**
```php
class EnrollmentObserver
{
    public function saved(Enrollment $enrollment): void
    {
        // No documentation
        if ($enrollment->isDirty('progress_percent') && $enrollment->progress_percent == 100) {
            event(new CourseCompleted($enrollment, $enrollment->user));
        }
    }
}
```

**Example (Correct 👍):**
```php
class EnrollmentObserver
{
    /**
     * Handle the Enrollment saved event.
     * Dispatches CourseCompleted event when progress reaches 100%.
     * Used for triggering completion-related actions like certificates and notifications.
     */
    public function saved(Enrollment $enrollment): void
    {
        if ($this->hasProgressReachedCompletion($enrollment)) {
            event(new CourseCompleted($enrollment, $enrollment->user));
        }
    }

    /**
     * Check if enrollment progress has just reached completion.
     */
    private function hasProgressReachedCompletion(Enrollment $enrollment): bool
    {
        return $enrollment->isDirty('progress_percent')
            && ((int) $enrollment->progress_percent) === 100;
    }
}
```
