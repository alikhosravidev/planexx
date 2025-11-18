# Testing Guide

> **Source**: `.windsurf/rules/testing.md`

## Main Mission
Write high-quality **Unit**, **Integration**, and **API/Web** tests that are readable, maintainable, and reliable.

## Critical First Step: Analysis & Clarification
**Golden Rule**: Never write tests before full clarification.

1. Thorough analysis of code and business
2. Identify ambiguities
3. Ask questions
4. Start after confirmation

## Test Types

### Unit Test
- **Focus**: One class/method in isolation
- **Dependencies**: ALL mocked (no framework, DB, external services)
- **Base Class Priority**:
  1. `Tests\Unit\PureUnitTestBase` (PREFERRED - no Laravel, very fast)
  2. `Tests\Unit\UnitTestBase` (if Laravel needed)
- **Speed**: Very fast (especially PureUnitTestBase)

### Integration Test
- **Focus**: Main unit + internal dependencies (DB, file system, cache)
- **Key Rule**: DO NOT mock data layer (Eloquent, DB Facade)
- **External Dependencies**: MUST be mocked (APIs, payment gateways, email)
- **Base Class**: `Tests\IntegrationTestBase`
- **Speed**: Slower, but essential

### API/Web Test
- **Focus**: Complete HTTP request (Controller, Middleware, Response)
- **Base Class**: `Tests\APITestBase`
- **Speed**: Slowest

## Quality Principles
- **Readability** - Clear purpose, Arrange-Act-Assert
- **Maintainability** - Independent tests, helper methods
- **Reliability** - Stable, deterministic results
- **Independence from Implementation** - Test behavior, not implementation details

## Best Practices

### One Test, One Scenario
```php
// ❌ Wrong - Multiple scenarios
public function test_enrollment_log_creation() {
    $log1 = $this->service->createVisitedLog(...);
    $log2 = $this->service->createPlayedLog(...);
}

// ✅ Correct - Separate scenarios
public function test_creates_visited_log_successfully() { }
public function test_creates_played_log_successfully() { }
```

### Test Behavior, Not Implementation
```php
// ❌ Wrong
$result = $service->calculate(3, 10);
$this->assertEquals($service->calculatePercentage(3, 10), $result);

// ✅ Correct
$result = $service->calculate(3, 10);
$this->assertEquals(30, $result);
```

### Manage Repeated Values
```php
// ✅ Correct
$expectedEmail = 'test@example.com';
$user = User::factory()->create(['email' => $expectedEmail]);
$this->assertEquals($expectedEmail, $user->email);
```

### Use Helper Methods
```php
private function makeTestUser(string $email = 'test@example.com'): User {
    return User::factory()->create(['email' => $email]);
}
```

### Precise Assertions
```php
// ✅ More precise
$this->assertSame(100, $result);
$this->assertArrayHasKey('email', $data);
$this->assertDatabaseHas(User::class, ['email' => $expectedEmail]);
```

## Final Checklist
- [ ] Tests check behavior, not implementation
- [ ] Each test covers one scenario
- [ ] Assertions use constant values
- [ ] Repeated values in variables
- [ ] Redundant tests removed
- [ ] Clear naming
- [ ] Helper methods reduce duplication
- [ ] Tests are independent

## Full Details
`.windsurf/rules/testing.md`

## Related
- Test Execution: `.claude/testing/execution.md`
- Factories: `.claude/infrastructure/database.md`
