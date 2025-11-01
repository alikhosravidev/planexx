---
trigger: manual
---

# Separation of Presentation Logic

**Key Concept:** Presentation logic must be separated from application logic to maintain clean architecture and ensure each layer has a single responsibility.

This document outlines the team standards for separating presentation logic from application logic in our layered architecture.

## Introduction

In layered architecture, each layer has distinct responsibilities. **Presentation layer** focuses on data presentation and user interactions; **application/domain layers** handle business logic. Mixing them causes bloated controllers, hard-to-test code, and violated **separation of concerns**.

## Core Principles

### 1. Presentation Layer Restrictions

The presentation layer **must not** perform complex calculations, data transformations, or business validations.

**❌ Incorrect: Controller with business logic**
```php
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();
        // ❌ Filtering logic in controller
        if ($request->has('category')) {
            $category = Category::find($request->input('category'));
            if ($category && $category->is_active) {
                $products->where('category_id', $category->id);
            }
        }
        return response()->json($products->get());
    }
}
```

**✅ Correct: Clean controller delegating to application layer**
```php
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}
    
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $products = $this->productService->getFilteredProducts($filters);
        
        return response()->json($products);
    }
}
```

### 2. Application Layer Responsibilities

The application layer **must** prepare data optimally for the presentation layer by transforming raw domain data into presentation-friendly formats.

**✅ Application service providing ready-to-use data**
```php
class ProductService
{
    public function getFilteredProducts(array $filters): ProductCollection
    {
        $query = Product::with('category');
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id'])
                  ->whereHas('category', fn($q) => $q->where('is_active', true));
        }
        return ProductCollection::make($query->paginate());
    }
}
```

### 3. Rule of Three: Conditional Logic Indicator

If the presentation layer contains more than 3 lines of conditional logic, it's a strong indicator that the application layer hasn't properly prepared the data.

**❌ Anti-pattern: Complex conditions in view**
```php
// In Blade template
@if($user->is_active)
    @if($user->subscription && $user->subscription->is_valid)
        @if($user->subscription->plan->price > 0)
            <span class="badge badge-success">Premium User</span>
        @else
            <span class="badge badge-info">Free User</span>
        @endif
    @else
        <span class="badge badge-warning">Inactive Subscription</span>
    @endif
@else
    <span class="badge badge-danger">Inactive User</span>
@endif
```

**✅ Better: Application layer provides status**
```php
// In controller or view composer
$userStatus = $user->getStatusBadge(); // Returns ['type' => 'success', 'text' => 'Premium User']

// In template
<span class="badge badge-{{ $userStatus['type'] }}">{{ $userStatus['text'] }}</span>
```

**✅ Even better: DTO with presentation-ready data**
```php
class UserProfileDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $statusBadgeType,
        public readonly string $statusBadgeText,
    ) {}
    
    public static function fromUser(User $user): self
    {
        return new self(
            name: $user->name,
            statusBadgeType: $user->getStatusBadgeType(),
            statusBadgeText: $user->getStatusBadgeText(),
        );
    }
}
```

### 4. Presentation Logic vs Application Logic

**Presentation Logic (Allowed in Controllers/Views):**
- Data formatting for display
- UI state management
- Basic data presence checks

**Application Logic (Must be in Services/Domain):**
- Business rule validations
- Complex calculations
- Data persistence

**❌ Incorrect: Business logic in controller**
```php
public function purchase(Request $request): JsonResponse
{
    $user = auth()->user();
    $product = Product::findOrFail($request->product_id);
    
    // ❌ Business logic in controller
    if ($user->balance < $product->price) {
        return response()->json(['error' => 'Insufficient funds'], 400);
    }
    
    DB::transaction(function () use ($user, $product) {
        $user->decrement('balance', $product->price);
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => $product->price,
        ]);
    });
    
    return response()->json(['success' => true]);
}
```

**✅ Correct: Clean controller with service delegation**
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

## Benefits

- **Maintainability**: Changes to business logic don't affect presentation
- **Testability**: Each layer can be tested independently
- **Reusability**: Business Logic can be reused across different presentation methods
- **Clarity**: Code responsibilities are clearly separated

## Implementation Guidelines

1. **Controllers should be thin**: Limit controllers to request validation, service calls, and response formatting
2. **Use DTOs for data transfer**: Structure data appropriately for presentation needs
3. **Apply the Rule of Three**: Review any conditional logic > 3 lines in presentation layer
4. **Services handle complexity**: Move all business logic to appropriate service classes

## Summary

**Key Guidelines:**
1. Presentation layer handles only presentation concerns
2. Application layer prepares data optimally for presentation
3. More than 3 lines of conditions in presentation = architecture smell
4. Business Logic belongs in services, not controllers
5. Use DTOs to structure presentation-ready data
