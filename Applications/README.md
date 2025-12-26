# راهنمای ساختار Applications و Assets

این پروژه از ساختار Modular Multi-Application برای مدیریت assets استفاده می‌کند.

## 📁 ساختار کلی

```
Applications/
├── AdminPanel/
│   ├── Resources/
│   │   ├── css/           # استایل‌های AdminPanel
│   │   ├── js/            # JavaScript های AdminPanel
│   │   ├── fonts/         # فونت‌های مخصوص AdminPanel
│   │   └── views/         # Blade templates
│   ├── Controllers/
│   └── AdminPanelServiceProvider.php
│
├── PWA/                   # (آینده)
│   └── Resources/...
│
└── MobileApp/            # (آینده)
    └── Resources/...

resources/                 # منابع مشترک بین تمام Applications
├── css/
│   ├── shared.css       # CSS مشترک (FontAwesome, Variables, Fonts, Tom-Select, Datepicker)
│   ├── variables.css    # CSS Variables
│   ├── tom-select.css   # Tom-Select Component
│   └── datepicker.css   # Persian Datepicker
├── js/
│   ├── shared.js        # JS مشترک (Bootstrap, Ziggy, AJAX)
│   ├── bootstrap.js
│   ├── bootstrap-di.js
│   ├── ziggy.js
│   ├── tom-select/      # Tom-Select Module (API, Config, Service)
│   └── datepicker/      # Persian Datepicker Module
└── fonts/
    └── fonts.css        # فونت‌های مشترک (Sahel)
```

## 🎯 اصول طراحی

### 1. Shared Resources
منابعی که بین تمام Applications مشترک هستند در `resources/` قرار دارند:
- **FontAwesome Icons**: برای همه استفاده می‌شود
- **Sahel Font**: فونت اصلی پروژه
- **CSS Variables**: متغیرهای design system
- **Bootstrap & Ziggy**: کتابخانه‌های core
- **AJAX Handler**: سیستم مدیریت درخواست‌های HTTP
- **Tom-Select**: کامپوننت Select Box با قابلیت جستجو و API
- **Persian Datepicker**: کامپوننت انتخاب تاریخ شمسی

### 2. Application-Specific Resources
هر Application منابع مخصوص خود را دارد:
- **CSS Files**: استایل‌های منحصر به فرد
- **JavaScript Files**: لوژیک‌های خاص Application
- **Views**: Blade templates
- **Optional Fonts**: فونت‌های اختصاصی (در صورت نیاز)

## 🔧 تنظیمات Vite

### Auto-Discovery
Vite به صورت خودکار تمام Applications را شناسایی و entry point‌های آن‌ها را می‌یابد:

```javascript
// vite.config.js
function discoverApplications() {
  // خواندن فولدرهای Applications
  // شناسایی Resources
  // ایجاد entry points
}
```

### Aliases
برای هر Application و Shared Resources alias تعریف شده:

```javascript
// در کدهای JS می‌توانید استفاده کنید:
import '@shared/js/some-utility'
import '@adminpanel/js/components/modal'
```

## 📝 نحوه استفاده در Blade Templates

### Layout اصلی AdminPanel

```blade
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    @routes

    {{-- بارگذاری Shared Resources و AdminPanel Assets --}}
    @vite([
        'resources/css/shared.css',
        'Applications/AdminPanel/Resources/css/app.css',
        'Applications/AdminPanel/Resources/js/app.js'
    ])
</head>
<body>
    @yield('content')
</body>
</html>
```

### صفحات خاص (با JS اضافی)

```blade
@extends('panel::layouts.app')

@section('content')
    {{-- محتوای صفحه --}}
@endsection

@push('scripts')
    @vite('Applications/AdminPanel/Resources/js/pages/documents.js')
@endpush
```

## 🚀 اضافه کردن Application جدید

### مثال: PWA Application

1. **ایجاد ساختار**:
```bash
mkdir -p Applications/PWA/Resources/{css,js,views}
```

2. **ایجاد Entry Points**:
```bash
# Applications/PWA/Resources/css/app.css
touch Applications/PWA/Resources/css/app.css

# Applications/PWA/Resources/js/app.js
touch Applications/PWA/Resources/js/app.js
```

3. **محتوای CSS**:
```css
/* Applications/PWA/Resources/css/app.css */

/* Tailwind */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* PWA Specific Styles */
.pwa-header {
  /* ... */
}
```

4. **محتوای JS**:
```javascript
// Applications/PWA/Resources/js/app.js

// Import PWA specific modules
import { initPWA } from './pwa-init.js';

document.addEventListener('DOMContentLoaded', () => {
  initPWA();
});
```

5. **ایجاد Service Provider**:
```php
<?php

namespace Applications\PWA;

use Illuminate\Support\ServiceProvider;

class PWAServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Applications/PWA/Resources/views'),
            'pwa'
        );

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
```

6. **ثبت در Laravel**:
```php
// config/app.php or bootstrap/providers.php
Applications\PWA\PWAServiceProvider::class,
```

7. **استفاده در Blade**:
```blade
<!DOCTYPE html>
<html>
<head>
    @vite([
        'resources/css/shared.css',
        'Applications/PWA/Resources/css/app.css',
        'Applications/PWA/Resources/js/app.js'
    ])
</head>
<body>
    {{-- PWA Content --}}
</body>
</html>
```

## 🔄 دستورات Development

```bash
# Development mode
npm run dev

# Production build
npm run build

# Watch mode
npm run watch

# پاکسازی build
npm run clean
```

## 🎨 TailwindCSS

Tailwind به صورت خودکار تمام فایل‌های موجود در مسیرهای زیر را اسکن می‌کند:
- `./resources/**/*.{js,css}`
- `./Applications/**/Resources/**/*.{blade.php,vue,js}`
- `./app/Core/**/Resources/views/**/*.js`

## 📦 Build Output

فایل‌های build شده در `public/build/` قرار می‌گیرند:
```
public/
└── build/
    ├── manifest.json
    └── assets/
        ├── shared-[hash].css
        ├── shared-[hash].js
        ├── app-[hash].css (AdminPanel)
        ├── app-[hash].js (AdminPanel)
        ├── documents-[hash].js
        └── ...
```

## 🔍 Best Practices

### ✅ انجام دهید
- از Shared Resources (`resources/`) برای کدهای مشترک استفاده کنید
- کامپوننت‌های UI مشترک (tom-select، datepicker) را در `resources/js/` قرار دهید
- از Aliases برای import استفاده کنید: `@shared-js/`, `@shared-css/`
- Entry point‌ها را minimal نگه دارید
- از Code Splitting برای صفحات بزرگ استفاده کنید
- نام‌گذاری واضح و consistent

### ❌ انجام ندهید
- کدهای مشترک را در هر Application تکرار نکنید
- از import مستقیم از `node_modules` در CSS (به جز در shared.css) استفاده نکنید
- FontAwesome، فونت‌های مشترک، یا UI components مشترک را در هر Application دوباره import نکنید
- از مسیرهای نسبی پیچیده استفاده نکنید، از Aliases استفاده کنید

## 🐛 Troubleshooting

### مشکل: Assets لود نمی‌شوند

1. بررسی کنید Vite server در حال اجرا است:
```bash
npm run dev
```

2. پاکسازی cache:
```bash
npm run clean
php artisan cache:clear
```

3. بررسی مسیرهای @vite در blade:
```bash
# باید به این شکل باشد:
@vite(['Applications/AdminPanel/Resources/css/app.css', ...])
```

### مشکل: Styles درست نمایش داده نمی‌شوند

1. بررسی Tailwind config
2. بررسی content paths در `tailwind.config.js`
3. Force rebuild:
```bash
npm run clean && npm run build
```

### مشکل: Hot reload کار نمی‌کند

1. بررسی `refresh: true` در `vite.config.js`
2. Clear browser cache
3. بررسی console برای خطاها

## 📚 مراجع

- [Laravel Vite Documentation](https://laravel.com/docs/vite)
- [TailwindCSS Documentation](https://tailwindcss.com)
- [Vite Documentation](https://vitejs.dev)
