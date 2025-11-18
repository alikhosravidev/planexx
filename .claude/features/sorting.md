# Sortable Models

> **Source**: `docs/sorting.md` (Persian)

## Quick Setup

### Step 1: Implement Interface & Trait
```php
use App\Services\SortOrder\Contracts\SortableEntity;
use App\Services\SortOrder\Traits\HasSorting;

class YourModel extends Model implements SortableEntity
{
    use HasSorting;
}
```

### Step 2: Configure Sort Column (Optional)
Override if column name is not `order`:
```php
public function sortingColumnName(): string
{
    return 'position';  // or 'sort_order', etc.
}
```

### Step 3: Set Sorting Scope (Optional)
For scoped sorting (e.g., per category):
```php
public function baseSortQuery(): Builder
{
    return $this->newQuery()
        ->where('category_id', $this->category_id)
        ->where('is_active', true);
}
```

## Features

### Auto-sort New Records
New records automatically placed at end.

### Reorder After Deletion
Remaining items automatically reordered.

### Query by Order
```php
YourModel::ordered()->get();
```

## Infrastructure
Uses observer that auto-applies to Sortable models. No additional setup needed.

## Full Details (Persian)
`docs/sorting.md`
