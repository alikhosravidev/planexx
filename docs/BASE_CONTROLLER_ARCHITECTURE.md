# BaseController Architecture

## Repository Configuration

### Required Properties
```php
public array $fieldSearchable = [
    'field_name' => 'operator',  // '=', 'like', 'in', 'between', etc.
];

public array $sortableFields = ['id', 'name', 'created_at', 'updated_at'];
```

### Example
```php
class CityRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
    ];
    
    public array $sortableFields = ['id', 'name', 'created_at', 'updated_at'];
}
```

## Controller Usage

### Simple (No Override Needed)
```php
class CityController extends BaseController
{
    public function __construct(
        CityRepository  $repository,
        CityTransformer $transformer,
        ResponseBuilder $response,
    ) {
        parent::__construct($repository, $transformer, $response);
    }
    // index() and show() inherited automatically
}
```

### Advanced (With Hooks)
```php
class UserController extends BaseController
{
    protected function customizeQuery(Builder $query, Request $request): Builder
    {
        return $query->where('status', 'active');
    }
    
    protected function authorizeShow($resource): void
    {
        if ($resource->user_id !== auth()->id()) {
            throw new \Exception('Unauthorized', 403);
        }
    }
}
```

## API Query Parameters

### Filtering
- Simple: `?filter[name]=value`
- Array (whereIn): `?filter[id][]=1&filter[id][]=2`
- Range: `?filter[price][gte]=100&filter[price][lte]=500`
- Nested: `?filter[user.name]=value`
- Operators: `gt`, `gte`, `lt`, `lte`, `like`, `in`, `not_in`

### Sorting
- JSON API: `?sort=-created_at,name` (- prefix = desc)
- Admin API: `?order_by=name&order_direction=asc`

### Pagination
- `?per_page=20&page=2` (max: 100, default: 15)

### Eager Loading
- `?includes=relation1,relation2`
- Must be in transformer's `$availableIncludes`

## Available Hooks

### Index Hooks
- `beforeIndex(Request $request): void`
- `afterIndex($results, Request $request): void`
- `customizeQuery(Builder $query, Request $request): Builder`

### Show Hooks
- `beforeShow(int|string $id, Request $request): void`
- `afterShow($resource, Request $request): void`
- `authorizeShow($resource): void`

## Configuration Properties

```php
protected int $maxPerPage = 100;
protected int $defaultPerPage = 15;
protected string $defaultSortField = 'created_at';
protected string $defaultSortDirection = 'desc';
```

## Security Features

- **Whitelist-only**: Only fields in `$fieldSearchable` and `$sortableFields` are allowed
- **SQL Injection Protection**: All queries use Query Builder (no DB::raw)
- **Includes Validation**: Only transformer's `$availableIncludes` are permitted
- **Input Validation**: per_page limited to max 100
- **N+1 Prevention**: Automatic eager loading with `with()`

## Response Format

### Success (Index)
```json
{
  "status": true,
  "result": [...],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 100,
      "last_page": 7,
      "from": 1,
      "to": 15
    },
    "links": {
      "first": "...",
      "last": "...",
      "prev": null,
      "next": "..."
    }
  }
}
```

### Error
```json
{
  "status": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid parameters"
  }
}
```

## Complex Filter Examples

```php
// Multiple filters
?filter[status]=active&filter[role]=admin

// Range filter
?filter[created_at][gte]=2024-01-01&filter[created_at][lte]=2024-12-31

// Array filter
?filter[category_id][]=1&filter[category_id][]=2&filter[category_id][]=3

// Combined
?filter[status]=active&sort=-created_at&per_page=20&includes=user
```
