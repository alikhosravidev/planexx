# Controllers

> **Source**: `.windsurf/rules/controller-architecture.md`

## Core Principle
Controllers should be **thin** - handle HTTP requests, validation, responses only. Delegate business logic to services.

## BaseController Usage

### Simple CRUD
```php
class CityController extends BaseController
{
    public function __construct(
        CityRepository $repository,
        CityTransformer $transformer,
        ResponseBuilder $response
    ) {
        parent::__construct($repository, $transformer, $response);
    }
    // index() and show() inherited automatically
}
```

### Repository Configuration
```php
class CityRepository extends BaseRepository
{
    public array $fieldSearchable = ['id' => '=', 'name' => 'like'];
    public array $sortableFields = ['id', 'name', 'created_at'];
}
```

### Available Hooks
- `beforeIndex($request)`
- `afterIndex($result, $request)`
- `customizeQuery($query, $request)`
- `beforeShow($id, $request)`
- `afterShow($result, $request)`
- `authorizeShow($model)`

## API Query Parameters

### Pagination
```
?per_page=15&page=1
```

### Filtering
```
?filter[name]=value
?filter[id][]=1&filter[id][]=2
?filter[price][gte]=100&filter[price][lte]=500
?search=term&searchFields=field&searchJoin=and
```

### Sorting
```
?order_by=field&order_direction=asc
?sort=-created_at,name
```

### Relations
```
?includes=relation1,relation2
```

### Field Selection
```
?field_sets={"entity":["field1","field2"]}
?excludes=field1,field2
```

## Best Practices
- Inject dependencies (repositories, services, transformers)
- Use Form Requests for validation
- Delegate business logic to services
- Return consistent responses via ResponseBuilder
- Apply user scoping in admin controllers

## Full Details
`.windsurf/rules/controller-architecture.md`

## Related
- Services: `.claude/application/services.md`
- Transformers: `.claude/presentation/transformers.md`
- API Design: `.claude/api/design.md`
