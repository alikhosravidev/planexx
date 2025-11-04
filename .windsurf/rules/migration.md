---
trigger: manual
---

# Migration Guidelines

## Core Principles

Migrations are critical for **database management** in Laravel. These guidelines ensure **smooth evolution** and prevent issues.

**Key Benefit:** Well-structured migrations make changes **reliable**, **reversible**, and **maintainable**.

## Migration Best Practices

### 1. Single Responsibility Principle

Each migration should perform **only one logical operation**.

**Good Practice:**
```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
}
```

**Bad Practice:**
```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamps();
    });
    
    DB::table('users')->insert([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

Common operations: creating tables, adding columns, modifying data, creating indexes.

### 2. Always Define Down Methods

Always implement the `down()` method to ensure migrations are reversible.

### 3. Separate Different Types of Operations

Separate structure changes from data operations into different migrations.

### 4. Handle Timeouts Properly

**Critical:** Laravel doesn't detect timeouts automatically. If a migration times out, Laravel may mark it as completed, leading to inconsistent states.

**Solution:** Use chunking for large data operations:

```php
public function up()
{
    User::chunk(1000, function ($users) {
        foreach ($users as $user) {
            $user->update(['status' => 'active']);
        }
    });
}
```

### 5. Immutability of Executed Migrations

**Critical Rule:** Once applied to production, never modify a migration. Always create new migrations for changes.

### 6. Hardcode Enums in Migrations

**Critical Rule:** Always hardcode enum values instead of referencing classes. Migrations are historical records and enum classes may change over time.

**Good Practice:**
```php
$table->enum('status', ['pending', 'processing', 'completed', 'cancelled']);
```

**Bad Practice:**
```php
use App\Enums\OrderStatus;
$table->enum('status', OrderStatus::values()); // DON'T DO THIS
```

### 7. Foreign Key Management Strategies

#### The Two-Phase Approach for Existing Tables

**Critical Rule:** Never add foreign keys to existing tables in one migration. Always use two separate migrations.

1. Add column (nullable).
2. Add constraint.

**Migration 1: Add the Column**
```php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_id')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('customer_id');
    });
}
```

**Migration 2: Add the Constraint**
```php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->foreign('customer_id')
            ->references('id')
            ->on('customers')
            ->cascadeOnDelete();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['customer_id']);
    });
}
```

#### Exception: Initial Table Creation

For new tables, it's safe to define foreign keys directly during creation:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});
```

### 8. Pre-Constraint Data Cleanup

**Critical Rule:** Always clean up invalid data before adding constraints. Use a three-phase approach:

**Migration 1: Clean Up Data**
```php
public function up()
{
    DB::table('orders')
        ->whereNotNull('customer_id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('customers')
                  ->whereColumn('customers.id', 'orders.customer_id');
        })
        ->update(['customer_id' => null]);
}

public function down()
{
    // Irreversible
}
```

**Migration 2: Add Column (If Needed)**
```php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_id')->nullable()->after('id');
    });
}
```

**Migration 3: Add Constraint**
```php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->foreign('customer_id')
            ->references('id')
            ->on('customers')
            ->nullOnDelete();
    });
}
```

## Conclusion

Following these guidelines maintains a **clean**, **reliable** database structure. Migrations are historical records—prioritize clarity.
