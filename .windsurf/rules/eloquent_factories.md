---
trigger: manual
---

### **Principle:**
Use Eloquent Factories to Simplify Test Data Creation

**Explanation:**
Use **Eloquent factories** to generate fake data for testing and seeding. They improve test readability and maintainability by abstracting data creation. Define default attributes in the **`definition`** method, use **Faker** for realistic data, and add **state methods** for variations. Avoid complex logic in factories. Use **sequences** for alternating values and define **relationships** properly. Place factories in `database/factories` and extend `Illuminate\Database\Eloquent\Factories\Factory`.

**Example (Wrong 👎):**
```php
// Manually creating repetitive test data, leading to bloated and error-prone tests
public function test_cart_creation()
{
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    $source = Source::create(['name' => 'Test Source']);
    $cart = Cart::create([
        'user_id' => $user->id,
        'ip' => '192.168.1.1',
        'status' => CartStatus::DRAFT,
        'source_id' => $source->id,
        'type' => CartType::CART,
    ]);
    $this->assertEquals(CartStatus::DRAFT, $cart->status);
}
```

**Example (Correct 👍):**
```php
class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'ip'        => $this->faker->ipv4,
            'status'    => $this->faker->randomElement(CartStatus::cases())->value,
            'source_id' => Source::factory(),
            'type'      => $this->faker->randomElement(CartType::cases())->value,
        ];
    }

    public function asDraft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CartStatus::DRAFT,
        ]);
    }

    public function asPaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CartStatus::PAID,
        ]);
    }
}

// Usage: Cart::factory()->asDraft()->create()
```

### **Principle:**
Utilize Factory States for Model Variations

---

**Explanation:**
Use **factory states** to define variations in model attributes. They allow reusable modifications for scenarios like suspended accounts. Define states as methods returning `$this->state()` with attribute changes. For soft-deletable models, use **`trashed()`**.

**Example (Wrong 👎):**
```php
// Creating multiple similar models with manual attribute overrides, reducing readability
$user1 = User::factory()->create(['status' => 'active']);
$user2 = User::factory()->create(['status' => 'suspended']);
```

**Example (Correct 👍):**
```php
class UserFactory extends Factory
{
    // ... definition method

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }
}

// Usage: User::factory()->suspended()->create()
```

### **Principle:**
Use Sequences for Alternating Attribute Values

---

**Explanation:**
Use **sequences** to cycle through attribute values for varied test data. Define them with the **`Sequence`** class or **`sequence()`** method using arrays or closures.

**Example (Wrong 👎):**
```php
// Manually alternating values, prone to errors in large datasets
$users = [];
$users[] = User::factory()->create(['role' => 'admin']);
$users[] = User::factory()->create(['role' => 'user']);
$users[] = User::factory()->create(['role' => 'admin']);
```

**Example (Correct 👍):**
```php
use Illuminate\Database\Eloquent\Factories\Sequence;

$users = User::factory()
    ->count(10)
    ->sequence(
        ['role' => 'admin'],
        ['role' => 'user']
    )
    ->create();
```

### **Principle:**
Define Relationships in Factories for Interconnected Models

---

**Explanation:**
Define **relationships** in factories to automatically create related models, ensuring data integrity. Use methods like **`has()`**, **`belongsTo()`**, etc.

**Example (Wrong 👎):**
```php
// Creating related models separately, increasing complexity
$user = User::factory()->create();
$post = Post::factory()->create(['user_id' => $user->id]);
```

**Example (Correct 👍):**
```php
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
        ];
    }
}

// Usage: Post::factory()->create() // Automatically creates user
```
