# راهنمای استفاده از Vite در پروژه ماژولار

## نمای کلی

این پروژه از سیستم Vite برای مدیریت assets استفاده می‌کند که به صورت ماژولار طراحی شده است. هر ماژول می‌تواند assets مخصوص به خود (CSS و JS) داشته باشد که به طور خودکار توسط Vite شناسایی و مدیریت می‌شوند.

## ساختار فایل‌ها

```
Modules/
├── {ModuleName}/
│   └── Resources/
│       ├── css/
│       │   └── {modulename}.css    # استایل‌های ماژول
│       ├── js/
│       │   └── {modulename}.js     # جاوا اسکریپت ماژول
│       └── views/
│           └── *.blade.php         # قالب‌های blade
```

## نحوه عملکرد

### 1. شناسایی خودکار Assets ماژول‌ها

Vite config (`vite.config.js`) به طور خودکار تمام فایل‌های ماژول‌ها را شناسایی و در لیست build قرار می‌دهد:

- `resources/css/app.css`
- `resources/js/user.js`
- `Modules/{ModuleName}/Resources/css/{modulename}.css`
- `Modules/{ModuleName}/Resources/js/{modulename}.js`

### 2. ایمپورت خودکار در JavaScript

فایل `resources/js/user.js` به طور خودکار CSS و JS هر ماژول را ایمپورت می‌کند:

```javascript
const modules = ['Notification', 'User'];

modules.forEach(module => {
    import(`../../Modules/${module}/Resources/css/${module.toLowerCase()}.css`)
        .catch(() => console.log(`No CSS found for module: ${module}`));

    import(`../../Modules/${module}/Resources/js/${module.toLowerCase()}.js`)
        .catch(() => console.log(`No JS found for module: ${module}`));
});
```

## استفاده در Blade Templates

### روش پیشنهادی (توصیه شده)

در layout اصلی خود (`layouts/app.blade.php`) تنها assets اصلی را لود کنید:

```blade
{{-- Layout اصلی --}}
@push('styles')
    @vite(['resources/css/app.css'])
@endpush

@push('scripts')
    @vite(['resources/js/user.js'])
@endpush
```

با این روش، تمام assets ماژول‌ها به طور خودکار لود می‌شوند.

### روش دستی (اختیاری)

اگر نیاز به کنترل بیشتر دارید، می‌توانید assets هر ماژول را به طور جداگانه لود کنید:

```blade
{{-- در یک blade template خاص --}}
@push('styles')
    @vite('Modules/User/Resources/css/user.css')
    @vite('Modules/Notification/Resources/css/notification.css')
@endpush

@push('scripts')
    @vite('Modules/User/Resources/js/user.js')
    @vite('Modules/Notification/Resources/js/notification.js')
@endpush
```

## دستورات Build

```bash
# توسعه
docker exec planexx_node npm run dev

# بیلد production
docker exec planexx_node npm run build

# watch mode
docker exec planexx_node npm run watch

# پاکسازی
docker exec planexx_node npm run clean
```

## نکات مهم

1. **نام‌گذاری فایل‌ها**: فایل‌های CSS و JS باید دقیقاً با نام ماژول (lowercase) باشد
2. **ایمپورت خودکار**: نیازی به ایمپورت دستی در user.js نیست
3. **TailwindCSS**: از `@apply` در فایل‌های ماژول استفاده نکنید یا از `@reference` استفاده کنید
4. **ترتیب لود**: assets اصلی ابتدا، سپس assets ماژول‌ها لود می‌شوند

## مثال استفاده از کلاس‌های ماژول

```blade
{{-- استفاده از کلاس‌های Notification --}}
<div class="notification-container">
    <div class="notification-toast">
        پیام اعلان
    </div>
</div>

{{-- استفاده از کلاس‌های User --}}
<div class="bg-primary text-white p-4">
    پنل کاربری
</div>
```

## افزودن ماژول جدید

1. ساختار دایرکتوری را ایجاد کنید
2. فایل‌های CSS و JS را اضافه کنید
3. نام ماژول را به آرایه `modules` در `user.js` اضافه کنید
4. از کلاس‌ها و توابع ماژول استفاده کنید
