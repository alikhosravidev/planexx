---
trigger: manual
---

# Controller Architecture

This document outlines the controller architecture in this modular Laravel project, including BaseController usage, adding new controllers, and common query parameters.

## Overview

Controllers follow a clean architecture: they handle HTTP requests, validation, and responses, but delegate business logic to services and data access to repositories. This ensures separation of concerns.

Key contracts:
- `BaseController`: Abstract base with repository, transformer, and response builder injection.
- `APIBaseController`: Extends BaseController for API-specific needs.
- `BaseRepository`: Interface for data access.
- `BaseModel`: Contract for models.
- `BaseTransformer`: For data transformation.
- `BaseRequest`: For request validation.

## Adding a New Controller

1. **Path**: Place in `app/Core/{Module}/Http/Controllers/V{Version}/{Type}/` (e.g., `app/Core/User/Http/Controllers/V1/Admin/NewController.php`).
2. **Class**: Extend `App\Http\Controllers\Controller`. Inject dependencies in constructor (repository, transformer, response).
3. **Methods**: Implement RESTful methods (`index`, `store`, `show`, `update`, `destroy`). Use injected services/repositories for logic.
4. **Routes**: Add in `app/Core/{Module}/Routes/V{Version}/{Type}/routes.php` using `Route::apiResource`.

Example:
```php
class NewController extends Controller
{
    public function __construct(
        private readonly BaseRepository $repository,
        private readonly BaseTransformer $transformer,
        private readonly ResponseBuilder $response,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = $this->repository->query()->with(['relations']);
        $result = $query->paginate($request->per_page ?? 15);
        return $this->response->success($this->transformer->transformCollection($result));
    }
}
```

## Using BaseController

For simple CRUD, extend BaseController and configure repository properties.

### Repository Configuration
```php
class CityRepository extends BaseRepository
{
    public array $fieldSearchable = ['id' => '=', 'name' => 'like'];
    public array $sortableFields = ['id', 'name', 'created_at', 'updated_at'];
}
```

### Simple Usage
```php
class CityController extends BaseController
{
    public function __construct(CityRepository $repository, CityTransformer $transformer, ResponseBuilder $response)
    {
        parent::__construct($repository, $transformer, $response);
    }
    // index() and show() inherited automatically
}
```

### Advanced Usage with Hooks
Override methods like `customizeQuery()` or `authorizeShow()` for custom logic.

Available hooks: `beforeIndex`, `afterIndex`, `customizeQuery`, `beforeShow`, `afterShow`, `authorizeShow`.

## API Query Parameters

BaseController supports advanced query parameters for pagination, filtering, sorting, and eager loading.

### Pagination
- `per_page`: Items per page (default: 15, max: 100)
- `page`: Page number (default: 1)

### Filtering
- Simple: `?filter[name]=value`
- Array: `?filter[id][]=1&filter[id][]=2`
- Range: `?filter[price][gte]=100&filter[price][lte]=500`
- Nested: `?filter[user.name]=value`
- Search: `?search=term&searchFields=field&searchJoin=and`
- Operators: `gt`, `gte`, `lt`, `lte`, `like`, `in`, `not_in`

### Sorting
- `?order_by=field&order_direction=asc`
- Or `?sort=-created_at,name` (JSON API style)

### Including Relations
- `?includes=relation1,relation2` (only allowed in transformer's `$availableIncludes`)

### Field Selection
- `?field_sets={"entity":["field1","field2"]}`
- `?excludes=field1,field2`

## Refactoring Existing Controllers

To refactor legacy controllers:
1. Move business logic to services in `app/Core/User/Services/`.
2. Ensure data access via repositories.
3. Use transformers for responses.
4. Inject dependencies properly.
5. Replace direct model calls with service calls.

Example:
```php
// Before
public function store(CreateRequest $request): JsonResponse
{
    $model = Address::create($request->validated());
    return response()->json(['data' => $model]);
}

// After
public function store(CreateRequest $request): JsonResponse
{
    $model = $this->addressService->createAddress($request->validated());
    return $this->response->success($this->transformer->transform($model));
}
```

## Best Practices

- Controllers should be thin: orchestration only.
- Inject dependencies (repositories, transformers, services).
- Follow REST conventions.
- Ensure consistent responses.
- For admin controllers, apply user scoping.
- Client controllers may have rate limits.

## Security and Response

- Whitelist-only: Only configured fields allowed.
- SQL injection protection via Query Builder.
- Includes validation.
- Input limits (per_page max 100).
- N+1 prevention with eager loading.

Success responses:
```json
{
  "status": true,
  "result": [...],
  "meta": { "pagination": {...} }
}
```

Error responses:
```json
{
  "status": false,
  "error": { "code": "CODE", "message": "Message" }
}
```
