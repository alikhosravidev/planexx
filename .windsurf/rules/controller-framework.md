---
trigger: manual
---

# Controller Framework

This document outlines the framework for adding or refactoring controllers in this project, based on the specific structure and contracts defined in `app/Contracts`.

## Overview

Controllers in this project follow a specific architecture to ensure separation of concerns. They do not contain business logic; instead, they delegate to services and repositories. Controllers handle HTTP requests, validation, and response formatting.

## Key Contracts

### Controller Contracts

- `App\Contracts\Controller\BaseController`: Abstract base class for controllers, injecting `BaseRepository`, `BaseTransformer`, and `ResponseBuilder`.
- `App\Contracts\Controller\APIBaseController`: Extends `BaseController` for API-specific controllers (currently empty, extends only).

### Related Contracts

- `App\Contracts\Repository\BaseRepository`: Interface for data access.
- `App\Contracts\Model\BaseModel`: Contract for models.
- `App\Contracts\Transformer\BaseTransformer`: For data transformation.
- `App\Contracts\Requests\BaseRequest`: For request validation.

## Adding a New Controller

### Step 1: Determine the Module and Version

Controllers are organized under `app/Core/{Module}/Http/Controllers/V{Version}/{Type}/`.

- Module: e.g., User
- Version: e.g., V1
- Type: Admin or Client

Example path: `app/Core/User/Http/Controllers/V1/Admin/NewController.php`

### Step 2: Create the Controller Class

Extend from `App\Http\Controllers\Controller` (not directly from Contracts, as Contracts are interfaces/abstracts).

Inject necessary dependencies in the constructor:

```php
<?php

namespace App\Core\User\Http\Controllers\V1\Admin;

use App\Contracts\Repository\BaseRepository;
use App\Contracts\Transformer\BaseTransformer;
use App\Services\ResponseBuilder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class NewController extends Controller
{
    public function __construct(
        private readonly BaseRepository $repository,
        private readonly BaseTransformer $transformer,
        private readonly ResponseBuilder $response,
    ) {}

    // Methods here
}
```

### Step 3: Define Methods

For RESTful controllers:

- `index(Request $request): JsonResponse` - List resources with pagination/filtering.
- `store(CreateRequest $request): JsonResponse` - Create new resource.
- `show(int $id): JsonResponse` - Get single resource.
- `update(UpdateRequest $request, int $id): JsonResponse` - Update resource.
- `destroy(int $id): JsonResponse` - Delete resource.

Methods should:

- Use injected repository for data operations.
- Call services for business logic (if services exist).
- Return JSON responses via `$this->response`.

Example:

```php
public function index(Request $request): JsonResponse
{
    $query = $this->repository->query()->with(['relations']);
    // Apply filters, search, order
    $result = $query->paginate($request->per_page ?? 15);
    return $this->response->success($this->transformer->transformCollection($result));
}
```

### Step 4: Add Routes

In `app/Core/{Module}/Routes/V{Version}/{Type}/routes.php`:

```php
use App\Core\User\Http\Controllers\V1\Admin\NewController;

Route::apiResource('news', NewController::class);
```

Or custom routes as needed.

### Step 5: Create Associated Files

- Request classes in `app/Core/User/Http/Requests/V1/Admin/`: Extend `BaseRequest`.
- If needed, create services in `app/Core/User/Services/`.
- Ensure entities/models implement `BaseModel`.

## Refactoring Existing Controllers

### Current Issues

Some controllers (e.g., `AddressController`) directly query models instead of using services/repositories fully. Refactor to:

1. Move business logic to dedicated services.
2. Ensure all data access goes through repositories.
3. Use transformers for response formatting.
4. Inject dependencies properly.

### Refactor Steps

1. Identify logic in controller methods.
2. Create or update service classes in `app/Core/User/Services/`.
3. Inject service into controller.
4. Replace direct model calls with service calls.
5. Update responses to use transformers.

Example refactor:

Before:

```php
public function store(CreateRequest $request): JsonResponse
{
    $data = $request->validated();
    $model = Address::create($data);
    return response()->json(['data' => $model]);
}
```

After:

```php
public function store(CreateRequest $request): JsonResponse
{
    $data = $request->validated();
    $model = $this->addressService->createAddress($data);
    return $this->response->success($this->transformer->transform($model));
}
```

## Best Practices

- Controllers should be thin: only orchestration, no logic.
- Use dependency injection for repositories, transformers, services.
- Follow REST conventions for method naming.
- Ensure responses are consistent with API standards (status, result, meta).
- For admin controllers, include user scoping (e.g., `where('user_id', auth()->id())`).
- Client controllers may have different permissions/rate limits.
