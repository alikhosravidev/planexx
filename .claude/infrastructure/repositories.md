# Repositories

> **Note**: Comprehensive documentation not yet available. Created from CLAUDE.md patterns.

## Purpose
Repositories provide **data access layer** with Criteria pattern, separating database operations from business logic.

## Repository Pattern with Criteria

### Basic Structure
```php
<?php

declare(strict_types=1);

namespace App\Core\Module\Repositories;

use App\Contracts\BaseRepository;

class EntityRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id' => '=',
        'name' => 'like',
        'email' => '=',
    ];

    public array $sortableFields = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function model(): string
    {
        return Entity::class;
    }
}
```

### Usage with Criteria
```php
// In service or controller
$user = $this->repository
    ->pushCriteria(new UserIdentifierCriteria($mobile))
    ->first();
```

## Repository Interface Binding
Bind in module ServiceProvider:
```php
public function register(): void
{
    $this->app->bind(
        EntityRepositoryInterface::class,
        EntityRepository::class
    );
}
```

## Criteria Pattern
Create specialized query criteria in `Repositories/Criteria/`:
```php
<?php

declare(strict_types=1);

namespace App\Core\Module\Repositories\Criteria;

use App\Contracts\Criteria\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class ActiveEntityCriteria implements CriteriaInterface
{
    public function apply(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
```

## Best Practices
1. **Single Responsibility** - One repository per entity
2. **Interface Segregation** - Define repository interfaces
3. **Criteria Pattern** - Complex queries via criteria
4. **Searchable Fields** - Define allowed search fields
5. **Sortable Fields** - Define allowed sort fields
6. **Type Hints** - Use return types

## Repository Methods
Common methods available:
- `find($id)`
- `findOrFail($id)`
- `first()`
- `all()`
- `create(array $data)`
- `update(array $data, $id)`
- `delete($id)`
- `pushCriteria(CriteriaInterface $criteria)`

## Related
- Entities: `.claude/domain/entities.md`
- Services: `.claude/application/services.md`
- Criteria Pattern: See `app/Contracts/BaseRepository.php`

## Documentation Gap
⚠️ **This is a documentation gap**. Detailed repository implementation guidelines need to be created.

See: CLAUDE.md for basic patterns
