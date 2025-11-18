# Database: Migrations & Factories

> **Sources**:
> - `.windsurf/rules/migration.md`
> - `.windsurf/rules/eloquent_factories.md`

## Migrations

### Core Principles

#### 1. Single Responsibility
Each migration performs **only one logical operation**.

#### 2. Always Define `down()` Methods
Migrations must be reversible.

#### 3. Separate Operation Types
Separate structure changes from data operations.

#### 4. Immutability Rule
**Never modify executed migrations in production**. Always create new migrations.

### Critical Rules

#### Foreign Keys: Two-Phase Approach
**Never add foreign keys to existing tables in one migration.**

**Phase 1: Add Column**
```php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_id')->nullable()->after('id');
    });
}
```

**Phase 2: Add Constraint**
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
```

**Exception**: New tables can define foreign keys directly:
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
});
```

#### Hardcode Enums
**Always hardcode enum values**, never reference enum classes:
```php
// ✅ Correct
$table->enum('status', ['pending', 'processing', 'completed']);

// ❌ Wrong
$table->enum('status', OrderStatus::values()); // DON'T!
```

**Why**: Migrations are historical records; enum classes may change.

#### Handle Timeouts
Use chunking for large data operations:
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

### Three-Phase Pattern (with Data Cleanup)
1. Clean up invalid data
2. Add column (if needed)
3. Add constraint

## Eloquent Factories

### Purpose
Generate fake data for testing and seeding.

### Basic Structure
```php
class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip' => $this->faker->ipv4,
            'status' => $this->faker->randomElement(CartStatus::cases())->value,
            'type' => $this->faker->randomElement(CartType::cases())->value,
        ];
    }
}
```

### Factory States
Define variations:
```php
public function asDraft(): static
{
    return $this->state(fn (array $attributes) => [
        'status' => CartStatus::DRAFT,
    ]);
}

// Usage: Cart::factory()->asDraft()->create()
```

### Sequences
Alternate attribute values:
```php
User::factory()
    ->count(10)
    ->sequence(
        ['role' => 'admin'],
        ['role' => 'user']
    )
    ->create();
```

### Define Relationships
```php
public function definition(): array
{
    return [
        'user_id' => User::factory(),  // Auto-creates related user
        'title' => $this->faker->sentence,
    ];
}
```

## Best Practices
- One class per file
- Use Faker for realistic data
- Define default attributes
- Use state methods for variations
- Avoid complex logic in factories

## Full Details
- Migrations: `.windsurf/rules/migration.md`
- Factories: `.windsurf/rules/eloquent_factories.md`
