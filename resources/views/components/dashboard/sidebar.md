# Sidebar Component

## Overview
کامپوننت Sidebar برای نمایش منوی کناری داشبورد که از زیرساخت Menu استفاده می‌کند.

## Usage

### Basic Usage
```blade
<x-dashboard.sidebar />
```

### With Custom Menu Name
```blade
<x-dashboard.sidebar menu-name="admin.sidebar" />
```

### With Current Page
```blade
<x-dashboard.sidebar 
    menu-name="dashboard.sidebar" 
    current-page="dashboard" 
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `menuName` | string | `'dashboard.sidebar'` | نام منویی که از MenuManager دریافت می‌شود |
| `currentPage` | string\|null | `null` | ID صفحه فعلی برای هایلایت کردن آیتم فعال |

## Menu Registration

منوها باید در ServiceProvider ثبت شوند:

```php
app('menu')->register('dashboard.sidebar', function ($menu) {
    $menu->item('داشبورد', 'dashboard')
        ->route('dashboard')
        ->icon('fa-solid fa-chart-line')
        ->order(1);
        
    $menu->item('کاربران', 'users')
        ->route('users.index')
        ->icon('fa-solid fa-users')
        ->order(2);
});
```

## Features

- ✅ دریافت خودکار آیتم‌ها از MenuManager
- ✅ پشتیبانی از permissions
- ✅ Cache برای بهبود performance
- ✅ نمایش responsive (desktop + mobile)
- ✅ هایلایت خودکار صفحه فعال
- ✅ انیمیشن‌های smooth

## Example

```blade
<x-layouts.app title="داشبورد">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar 
            menu-name="dashboard.sidebar" 
            current-page="dashboard" 
        />
        
        <main class="flex-1">
            <!-- محتوای صفحه -->
        </main>
    </div>
</x-layouts.app>
```
