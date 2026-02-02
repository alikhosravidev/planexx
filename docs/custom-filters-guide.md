# Custom Filters در Repository Pattern

## معرفی

Custom Filters یک مکانیزم قدرتمند برای اعمال فیلترهای domain-specific پیچیده است که از **Criteria Pattern** استفاده می‌کند. این قابلیت به شما امکان می‌دهد query های پیچیده و reusable را در کلاس‌های جداگانه تعریف کرده و از طریق API یا کد به راحتی استفاده کنید.

## مزایا

- ✅ **Separation of Concerns**: منطق فیلتر از Repository و Controller جدا می‌شود
- ✅ **Reusability**: یک بار تعریف، چندین بار استفاده
- ✅ **Testability**: هر Criteria به صورت مستقل قابل تست است
- ✅ **Flexibility**: قابل استفاده از API و کد
- ✅ **Type Safety**: استفاده از Type Hints و Interfaces

---

## نحوه پیاده‌سازی

### مرحله 1: ساخت Criteria Class

هر Custom Filter یک کلاس Criteria است که `CriteriaInterface` را implement می‌کند:

```php
<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories\Criteria;

use App\Contracts\Repository\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class UserAccessibleWorkflowsCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly ?int $userId = null,
        private readonly bool $includeInactive = false
    ) {
    }

    public function apply(Builder $query): Builder
    {
        $userId = $this->userId ?? auth()->id();

        if (!$userId) {
            return $query->whereRaw('1 = 0');
        }

        $query->where(function (Builder $q) use ($userId) {
            $q->whereHas('allowedRoles', function ($roleQuery) use ($userId) {
                $roleQuery->whereHas('users', fn($uq) => $uq->where('users.id', $userId));
            })
            ->orWhere('owner_id', $userId)
            ->orWhereHas('department', fn($dq) => $dq->where('manager_id', $userId));
        });

        if (!$this->includeInactive) {
            $query->where('is_active', true);
        }

        return $query;
    }
}
```

### مرحله 2: ثبت در Repository

در Repository مربوطه، فیلتر را در array `$customFilters` ثبت کنید:

```php
<?php

declare(strict_types=1);

namespace App\Core\BPMS\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Repositories\Criteria\UserAccessibleWorkflowsCriteria;

class WorkflowRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'   => '=',
        'name' => 'like',
    ];

    // Custom Filters Configuration
    protected array $customFilters = [
        'user_accessible' => UserAccessibleWorkflowsCriteria::class,
    ];

    public function model(): string
    {
        return Workflow::class;
    }
}
```

---

## استفاده از API

### فیلتر ساده (بدون پارامتر)

```http
GET /api/v1/admin/bpms/workflows?custom_filters[user_accessible]=true
```

### چند فیلتر همزمان

```http
GET /api/v1/admin/bpms/workflows?custom_filters[user_accessible]=true&custom_filters[has_active_tasks]=true
```

### فیلتر با پارامتر عددی

```http
GET /api/v1/admin/bpms/workflows?custom_filters[minimum_states]=3
```

### فیلتر با چند پارامتر

```http
GET /api/v1/admin/bpms/workflows?custom_filters[user_accessible][]=123&custom_filters[user_accessible][]=true
```

### ترکیب با فیلترهای معمولی

```http
GET /api/v1/admin/bpms/workflows?filter[is_active]=1&custom_filters[user_accessible]=true&sort=-created_at
```

### استفاده از JSON

```http
GET /api/v1/admin/bpms/workflows?custom_filters={"user_accessible":[123,true],"minimum_states":2}
```

---

## استفاده در کد PHP

### استفاده ساده

```php
// فیلتر با پارامترهای پیش‌فرض (کاربر فعلی)
$this->repository->applyCustomFilter('user_accessible');
$workflows = $this->repository->paginate(15);
```

### با پارامتر

```php
// فیلتر با user ID مشخص
$this->repository->applyCustomFilter('user_accessible', [123]);

// فیلتر با چند پارامتر
$this->repository->applyCustomFilter('user_accessible', [123, true]);
```

### چندین فیلتر (Chaining)

```php
$this->repository
    ->applyCustomFilter('user_accessible')
    ->applyCustomFilter('minimum_states', 3)
    ->applyCustomFilter('has_active_tasks');

$workflows = $this->repository->all();
```

### بررسی وجود فیلتر

```php
if ($this->repository->hasCustomFilter('user_accessible')) {
    $this->repository->applyCustomFilter('user_accessible');
}
```

### دریافت لیست فیلترهای موجود

```php
$availableFilters = $this->repository->getAvailableCustomFilters();
// ['user_accessible', 'has_active_tasks', 'minimum_states']
```

---

## Convention نام‌گذاری

| نوع | قاعده | مثال |
|-----|------|-------|
| Key در Array | snake_case | `user_accessible` |
| Criteria Class | PascalCase + `Criteria` | `UserAccessibleWorkflowsCriteria` |
| Namespace | `App\{Module}\Repositories\Criteria` | `App\Core\BPMS\Repositories\Criteria` |

---

## ساختار فایل‌ها

```
app/
├── Contracts/
│   └── Repository/
│       ├── BaseRepository.php      # شامل متدهای customFilter
│       └── CriteriaInterface.php   # Interface برای Criteria ها
├── Core/
│   └── BPMS/
│       ├── Entities/
│       │   └── Workflow.php
│       └── Repositories/
│           ├── Criteria/
│           │   ├── UserAccessibleWorkflowsCriteria.php
│           │   └── WorkflowMinimumStatesCriteria.php
│           └── WorkflowRepository.php
```

---

## بهترین شیوه‌ها

### ✅ Do

1. **یک مسئولیت واحد**: هر Criteria فقط یک نوع فیلتر را انجام دهد
2. **استفاده از Type Hints**: تمام پارامترها و return type ها را مشخص کنید
3. **Default Values**: برای پارامترهای اختیاری مقادیر پیش‌فرض تعریف کنید
4. **نام‌گذاری واضح**: نام Criteria باید هدف آن را مشخص کند
5. **Testable**: Criteria ها را مستقل از Repository تست کنید

### ❌ Don't

1. **منطق Business**: فقط Query بنویسید، نه Business Logic
2. **وابستگی به Request**: از Request مستقیم استفاده نکنید
3. **Side Effects**: Criteria نباید state را تغییر دهد
4. **پیچیدگی زیاد**: اگر خیلی پیچیده شد، به چند Criteria تقسیم کنید

---

## مثال‌های پیشرفته

### Criteria با پارامتر پیچیده

```php
class WorkflowByDateRangeCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly ?Carbon $from = null,
        private readonly ?Carbon $to = null,
        private readonly string $dateField = 'created_at'
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->from) {
            $query->where($this->dateField, '>=', $this->from);
        }

        if ($this->to) {
            $query->where($this->dateField, '<=', $this->to);
        }

        return $query;
    }
}
```

### Criteria با Scope های مختلف

```php
class WorkflowByScopeCriteria implements CriteriaInterface
{
    public function __construct(
        private readonly string $scope = 'all' // 'mine', 'team', 'department', 'all'
    ) {
    }

    public function apply(Builder $query): Builder
    {
        $userId = auth()->id();

        return match($this->scope) {
            'mine' => $query->where('owner_id', $userId),
            'team' => $query->whereHas('team.members', fn($q) => $q->where('user_id', $userId)),
            'department' => $query->whereHas('department.users', fn($q) => $q->where('user_id', $userId)),
            default => $query,
        };
    }
}
```

---

## خطاها و Debugging

### خطای "Criteria class not found"

```
Custom filter criteria class App\...\MyCriteria not found
```

**راه حل**:
- مطمئن شوید Criteria class وجود دارد
- Namespace صحیح باشد
- `composer dump-autoload` را اجرا کنید

### خطای "must implement CriteriaInterface"

```
Custom filter MyCriteria must implement CriteriaInterface
```

**راه حل**: Criteria باید `CriteriaInterface` را implement کند:

```php
use App\Contracts\Repository\CriteriaInterface;

class MyCriteria implements CriteriaInterface
{
    public function apply(Builder $query): Builder
    {
        // ...
    }
}
```

### فیلتر اعمال نمی‌شود

1. بررسی کنید فیلتر در `$customFilters` Repository ثبت شده باشد
2. نام فیلتر در request باید دقیقاً مطابق key در array باشد
3. Log کنید: `Log::info($this->repository->getAvailableCustomFilters())`

---

## API Reference

### BaseRepository Methods

| Method | Description | Return |
|--------|-------------|--------|
| `applyCustomFilter(string $name, mixed $params = null)` | اعمال یک custom filter | `self` |
| `hasCustomFilter(string $name)` | بررسی وجود filter | `bool` |
| `getAvailableCustomFilters()` | لیست filter های موجود | `array` |

### CriteriaInterface

```php
interface CriteriaInterface
{
    public function apply(Builder $query): Builder;
}
```
