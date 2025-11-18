# Services (Application Layer)

> **Note**: This documentation is not yet available in source files. This is a gap to be filled.

## Purpose
Services contain **business logic** that doesn't belong in Controllers, Entities, or Repositories.

## When to Use Services

### ✅ Use Services For:
- Complex business logic
- Multi-entity operations
- External API calls
- Transaction management
- Domain workflows
- Business rule enforcement

### ❌ Don't Use Services For:
- Simple CRUD (use Repository + Controller)
- Data transformation (use Mappers/Transformers)
- Validation (use Form Requests)
- Database queries (use Repositories)

## Service Structure
```php
<?php

declare(strict_types=1);

namespace App\Core\Module\Services;

use App\Core\Module\Contracts\RepositoryInterface;
use App\Core\Module\DTOs\EntityDTO;

class EntityService
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        // Inject other dependencies
    ) {}

    public function create(EntityDTO $dto): Entity
    {
        // Business logic here
        // Validate business rules
        // Create entity
        // Trigger events if needed

        return $this->repository->create($dto->toArray());
    }

    public function complexBusinessOperation(/* params */): Result
    {
        // Multi-step business logic
        // Orchestrate multiple repositories
        // Handle transactions

        return new Result(/* ... */);
    }
}
```

## Example: Order Processing Service
```php
class OrderProcessingService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly PaymentGateway $paymentGateway,
        private readonly NotificationService $notificationService
    ) {}

    public function processOrder(OrderDTO $dto): OrderResult
    {
        DB::transaction(function () use ($dto) {
            // Business logic
            $order = $this->orderRepository->create($dto->toArray());

            // Process payment
            $payment = $this->paymentGateway->charge($order);

            // Send notifications
            $this->notificationService->sendOrderConfirmation($order);

            return new OrderResult(success: true, order: $order);
        });
    }
}
```

## Best Practices
1. **Single Responsibility** - Each service should have one clear purpose
2. **Dependency Injection** - Inject all dependencies via constructor
3. **Type Safety** - Use DTOs for input, return typed results
4. **Transaction Management** - Use DB::transaction for multi-step operations
5. **Event Dispatching** - Dispatch domain events for important occurrences
6. **Error Handling** - Use custom exceptions, not generic ones

## Service Registration
Register in module ServiceProvider:
```php
public function register(): void
{
    $this->app->bind(EntityServiceInterface::class, EntityService::class);
}
```

## Testing Services
Services should be unit tested with mocked dependencies:
```php
class OrderProcessingServiceTest extends TestCase
{
    public function test_processes_order_successfully(): void
    {
        $repository = Mockery::mock(OrderRepository::class);
        $gateway = Mockery::mock(PaymentGateway::class);
        $notifications = Mockery::mock(NotificationService::class);

        $service = new OrderProcessingService($repository, $gateway, $notifications);

        // Test logic
    }
}
```

## Related
- DTOs: `.claude/application/dtos.md`
- Repositories: `.claude/infrastructure/repositories.md`
- Controllers: `.claude/presentation/controllers.md`
- Events: `.claude/events.md`

## Documentation Gap
⚠️ **This is a documentation gap**. Detailed service implementation guidelines need to be created based on existing code patterns.

See gap analysis report for more details.
