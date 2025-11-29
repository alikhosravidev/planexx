# Services Directory

این دایرکتوری شامل سرویس‌های مشترک پروژه است که از معماری Registry System استفاده می‌کنند.

## 📁 Structure

```
Services/
├── Menu/              # سیستم مدیریت منوها
│   ├── MenuManager.php
│   ├── MenuItem.php
│   ├── MenuGroup.php
│   └── MenuBuilder.php
│
├── Stats/             # سیستم مدیریت آمارها
│   ├── StatManager.php
│   ├── StatItem.php
│   └── StatBuilder.php
│
└── QuickAccess/       # سیستم مدیریت دسترسی‌های سریع
    ├── QuickAccessManager.php
    ├── QuickAccessItem.php
    └── QuickAccessBuilder.php
```

## 🎯 Registry System

تمام این سرویس‌ها از یک معماری یکپارچه به نام **Registry System** استفاده می‌کنند که شامل:

### Core Interfaces (در `app/Contracts/Registry/`)
- `RegistryItemInterface` - Interface پایه برای آیتم‌ها
- `RegistryBuilderInterface` - Interface برای سازنده‌ها
- `RegistryManagerInterface` - Interface برای مدیریت
- `RegistrarInterface` - Interface برای ثبت‌کننده‌ها

## 📖 Documentation

برای اطلاعات کامل و نحوه استفاده، به فایل زیر مراجعه کنید:

👉 [Registry System Documentation](../../.claude/registry-system.md)

## 🚀 Quick Start

### 1. Register Service in Provider

```php
public function register(): void
{
    $this->app->singleton('stats', fn() => new StatManager());
}

public function boot(): void
{
    app('stats')->registerBy(DashboardStatsRegistrar::class);
}
```

### 2. Create Registrar Class

```php
class DashboardStatsRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.stats', function (StatBuilder $builder) {
            $builder->stat('عنوان', 'id')
                ->value(100)
                ->icon('fa-solid fa-chart')
                ->color('blue')
                ->order(1);
        });
    }
}
```

### 3. Use in Controller

```php
$stats = app('stats')->toArray('dashboard.stats');
return view('dashboard', compact('stats'));
```

## 🔗 Related

- Menu System: `app/Services/Menu/`
- Stats System: `app/Services/Stats/`
- Quick Access System: `app/Services/QuickAccess/`
- Registrars: `app/Registrars/`
- Contracts: `app/Contracts/Registry/`
