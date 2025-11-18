# Presentation Logic Separation

> **Source**: `.windsurf/rules/presentation-logic-separation.md`

## Core Principle
**Presentation logic** must be separated from **application logic**.

## Rule of Three
If presentation layer has **more than 3 lines of conditional logic**, application layer hasn't properly prepared the data.

## Presentation vs Application Logic

### Presentation Logic (Allowed in Controllers/Views)
- Data formatting for display
- UI state management
- Basic data presence checks

### Application Logic (Must be in Services/Domain)
- Business rule validations
- Complex calculations
- Data persistence

## Examples

### ❌ Wrong: Business Logic in Controller
```php
public function purchase(Request $request): JsonResponse
{
    $user = auth()->user();
    $product = Product::findOrFail($request->product_id);

    // ❌ Business logic here
    if ($user->balance < $product->price) {
        return response()->json(['error' => 'Insufficient funds'], 400);
    }

    DB::transaction(function () use ($user, $product) {
        $user->decrement('balance', $product->price);
        Purchase::create([...]);
    });
}
```

### ✅ Correct: Delegate to Service
```php
public function purchase(PurchaseRequest $request, PurchaseService $service): JsonResponse
{
    try {
        $result = $service->processPurchase(
            auth()->id(),
            $request->validated('product_id')
        );

        return response()->json([
            'success' => true,
            'purchase_id' => $result->purchaseId,
            'remaining_balance' => $result->remainingBalance,
        ]);
    } catch (PurchaseException $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
```

### ❌ Wrong: Complex Conditions in View
```blade
@if($user->is_active)
    @if($user->subscription && $user->subscription->is_valid)
        @if($user->subscription->plan->price > 0)
            <span>Premium User</span>
        @else
            <span>Free User</span>
        @endif
    @else
        <span>Inactive Subscription</span>
    @endif
@endif
```

### ✅ Correct: Application Layer Provides Status
```php
// In controller/service
$userStatus = UserStatusDTO::fromUser($user);

// In view
<span class="badge badge-{{ $userStatus->type }}">
    {{ $userStatus->text }}
</span>
```

## Benefits
- **Maintainability** - Changes isolated
- **Testability** - Independent layer testing
- **Reusability** - Business logic reusable
- **Clarity** - Responsibilities clear

## Implementation Guidelines
1. Controllers should be thin
2. Use DTOs for presentation-ready data
3. Apply Rule of Three
4. Services handle complexity

## Full Details
`.windsurf/rules/presentation-logic-separation.md`
