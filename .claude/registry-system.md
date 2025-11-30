# 🏗️ Registry System Infrastructure

## 📋 Overview

سیستم Registry یک زیرساخت یکپارچه برای مدیریت آیتم‌های قابل ثبت در پروژه است که شامل Menu، Stats و Quick Access می‌شود.

---

## 🎯 Architecture

### Core Interfaces

#### 1. `RegistryItemInterface`
Interface پایه برای تمام آیتم‌های قابل ثبت:

```php
interface RegistryItemInterface extends Arrayable
{
    public function getId(): string;
    public function getTitle(): string;
    public function getOrder(): int;
    public function getPermission(): ?string;
    public function isActive(): bool;
    public function getType(): string;
}
```

#### 2. `RegistryBuilderInterface`
Interface برای سازنده‌های آیتم‌ها:

```php
interface RegistryBuilderInterface
{
    public function add(RegistryItemInterface $item): static;
    public function getItems(): array;
}
```

#### 3. `RegistryManagerInterface`
Interface برای مدیریت ثبت و بازیابی آیتم‌ها:

```php
interface RegistryManagerInterface
{
    public function register(string $registryName, callable $callback): static;
    public function get(string $registryName): Collection;
    public function toArray(string $registryName): array;
    public function clearCache(?string $registryName = null): void;
    public function has(string $registryName): bool;
}
```

#### 4. `RegistrarInterface`
Interface برای کلاس‌های ثبت‌کننده:

```php
interface RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void;
}
```

---

## 📊 Stats System

### Classes

- **`StatItem`**: آیتم آماری با ویژگی‌های: title, value, icon, color, description, change
- **`StatBuilder`**: سازنده آیتم‌های آماری
- **`StatManager`**: مدیریت ثبت و بازیابی آمارها

### Usage Example

```php
namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Stats\StatBuilder;

class DashboardStatsRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.stats', function (StatBuilder $builder) {
            $builder->stat('کل کاربران', 'total-users')
                ->value(fn () => User::count())
                ->icon('fa-solid fa-users')
                ->color('blue')
                ->description('تعداد کل کاربران سیستم')
                ->order(1);

            $builder->stat('کاربران فعال', 'active-users')
                ->value(fn () => User::where('is_active', true)->count())
                ->icon('fa-solid fa-user-check')
                ->color('green')
                ->change('+12%', 'positive')
                ->order(2);
        });
    }
}
```

### Available Methods

```php
$stat = StatItem::make('عنوان', 'id')
    ->value(100)                           // مقدار (عدد، رشته یا Closure)
    ->icon('fa-solid fa-chart-line')       // آیکون
    ->color('blue')                        // رنگ (blue, green, purple, orange)
    ->description('توضیحات')              // توضیحات
    ->change('+10%', 'positive')           // تغییرات (positive, negative, neutral)
    ->order(1)                             // ترتیب نمایش
    ->permission('view.stats')             // دسترسی مورد نیاز
    ->active(true)                         // وضعیت فعال/غیرفعال
    ->activeWhen(fn($request) => ...)      // شرط فعال بودن
    ->attributes(['key' => 'value']);      // ویژگی‌های اضافی
```

---

## ⚡ Quick Access System

### Classes

- **`QuickAccessItem`**: آیتم دسترسی سریع با ویژگی‌های: title, url, icon, color, enabled
- **`QuickAccessBuilder`**: سازنده آیتم‌های دسترسی سریع
- **`QuickAccessManager`**: مدیریت ثبت و بازیابی دسترسی‌های سریع

### Usage Example

```php
namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class DashboardQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('ساختار سازمانی', 'organization')
                ->route('org.dashboard')
                ->icon('fa-solid fa-sitemap')
                ->color('blue')
                ->enabled(true)
                ->order(1);

            $builder->item('گزارشات', 'reports')
                ->url('/reports')
                ->icon('fa-solid fa-chart-bar')
                ->color('orange')
                ->enabled(false)  // به زودی
                ->order(2);
        });
    }
}
```

### Available Methods

```php
$item = QuickAccessItem::make('عنوان', 'id')
    ->url('/path')                         // URL مستقیم
    ->route('route.name', ['id' => 1])     // Laravel Route
    ->icon('fa-solid fa-home')             // آیکون
    ->color('blue')                        // رنگ
    ->enabled(true)                        // فعال/غیرفعال (برای "به زودی")
    ->order(1)                             // ترتیب نمایش
    ->permission('access.module')          // دسترسی مورد نیاز
    ->target('_blank')                     // target برای لینک
    ->active(true)                         // وضعیت فعال
    ->activeWhen(fn($request) => ...)      // شرط فعال بودن
    ->enabledWhen(fn($request) => ...)     // شرط enabled بودن
    ->attributes(['key' => 'value']);      // ویژگی‌های اضافی
```

---

## 📝 Menu System (Existing)

سیستم Menu از قبل وجود دارد و از همین معماری پیروی می‌کند:

```php
$menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
    $menu->item('داشبورد', 'dashboard')
        ->route('web.dashboard')
        ->icon('fa-solid fa-chart-line')
        ->order(1);

    $menu->group('مدیریت', 'management')
        ->icon('fa-solid fa-cog')
        ->children(function (MenuBuilder $menu) {
            $menu->item('کاربران', 'users')
                ->route('users.index')
                ->icon('fa-solid fa-users');
        });
});
```

---

## 🔧 Service Provider Registration

در `AppServiceProvider` یا Service Provider مربوط به ماژول:

```php
use App\Services\Stats\StatManager;
use App\Services\QuickAccess\QuickAccessManager;

public function register(): void
{
    // Register Stats Manager
    $this->app->singleton('stats', function () {
        return new StatManager();
    });

    // Register Quick Access Manager
    $this->app->singleton('quick-access', function () {
        return new QuickAccessManager();
    });
}

public function boot(): void
{
    // Register Stats
    app('stats')->registerBy(DashboardStatsRegistrar::class);

    // Register Quick Access
    app('quick-access')->registerBy(DashboardQuickAccessRegistrar::class);
}
```

---

## 🎨 Blade Components Usage

### Stats Component

```blade
<x-dashboard.stat-card
    title="کل کاربران"
    value="1,234"
    icon="fa-solid fa-users"
    color="blue"
    change="+12%"
    changeType="positive"
/>
```

### Quick Access Component

```blade
<x-dashboard.quick-access :modules="$quickAccessItems" />
```

در Controller:

```php
public function dashboard()
{
    $stats = app('stats')->toArray('dashboard.stats');
    $quickAccess = app('quick-access')->toArray('dashboard.quick-access');

    return view('dashboard', [
        'stats' => $stats,
        'quickAccess' => $quickAccess,
    ]);
}
```

---

## ⚙️ Configuration

در `config/services.php`:

```php
'stats' => [
    'cache_enabled' => true,
    'cache_ttl'     => 300,      // 5 minutes
    'cache_prefix'  => 'stats_',
],

'quick_access' => [
    'cache_enabled' => true,
    'cache_ttl'     => 3600,     // 1 hour
    'cache_prefix'  => 'quick_access_',
],
```

---

## 🔐 Permission System

تمام سیستم‌ها از Permission پشتیبانی می‌کنند:

```php
$builder->stat('آمار حساس', 'sensitive-stat')
    ->value(100)
    ->permission('view.sensitive.stats')  // فقط کاربران با این دسترسی می‌بینند
    ->order(1);
```

---

## 💾 Cache Management

### Clear Cache

```php
// Clear specific registry
app('stats')->clearCache('dashboard.stats');

// Clear all stats cache
app('stats')->clearCache();

// Disable cache temporarily
app('stats')->withoutCache()->get('dashboard.stats');

// Custom cache TTL
app('stats')->withCache(600)->get('dashboard.stats');
```

---

## 🎯 Best Practices

### 1. Naming Convention
- Registry names: `{context}.{type}` (e.g., `dashboard.stats`, `org.quick-access`)
- Item IDs: `kebab-case` (e.g., `total-users`, `active-tasks`)

### 2. Order Management
- Use increments of 10 (10, 20, 30) to allow easy insertion

### 3. Dynamic Values
- Use Closures for dynamic values to avoid N+1 queries:
```php
->value(fn () => User::count())  // ✅ Good
->value(User::count())            // ❌ Bad (executes immediately)
```

### 4. Module Isolation
- Each module should have its own Registrar class
- Register in module's Service Provider

### 5. Cache Strategy
- Stats: Short TTL (5 minutes) - data changes frequently
- Quick Access: Long TTL (1 hour) - structure rarely changes
- Menu: Long TTL (1 hour) - structure rarely changes

---

## 🔄 Extending System

برای افزودن نوع جدید (مثلاً Notifications):

1. Create Item class implementing `RegistryItemInterface`
2. Create Builder class implementing `RegistryBuilderInterface`
3. Create Manager class implementing `RegistryManagerInterface`
4. Register in Service Provider
5. Create Registrar classes for modules

---

## 📦 Module Integration Example

```php
namespace Modules\MyModule\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\Stats\StatBuilder;

class MyModuleStatsRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.stats', function (StatBuilder $builder) {
            $builder->stat('آمار ماژول من', 'my-module-stat')
                ->value(fn () => MyModel::count())
                ->icon('fa-solid fa-box')
                ->color('purple')
                ->permission('view.my.module')
                ->order(100);
        });
    }
}
```

در Service Provider ماژول:

```php
public function boot(): void
{
    app('stats')->registerBy(MyModuleStatsRegistrar::class);
}
```

---

## ✅ Summary

این زیرساخت یکپارچه امکانات زیر را فراهم می‌کند:

- ✅ Interface های استاندارد و قابل توسعه
- ✅ سیستم کش هوشمند بر اساس کاربر
- ✅ پشتیبانی کامل از Permission
- ✅ قابلیت فعال/غیرفعال کردن آیتم‌ها
- ✅ مرتب‌سازی خودکار
- ✅ ساختار modular برای ماژول‌ها
- ✅ Fluent API برای سهولت استفاده
- ✅ Type-safe با PHP 8.x
