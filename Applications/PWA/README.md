# PWA Application

این اپلیکیشن PWA (Progressive Web App) بخشی از سیستم Planexx است که تجربه موبایل‌فرست و قابلیت نصب روی دستگاه‌های موبایل را فراهم می‌کند.

## ✨ ویژگی‌های PWA

### 🚀 قابلیت‌های پیشرفته
- **Installable**: قابلیت نصب بر روی دستگاه موبایل/دسکتاپ
- **Offline Support**: کار کردن در حالت آفلاین با Service Worker
- **Push Notifications**: دریافت اعلان‌ها حتی در حالت بسته بودن اپلیکیشن
- **Background Sync**: همگام‌سازی خودکار داده‌ها در پس‌زمینه
- **App-like Experience**: تجربه‌ای شبیه اپلیکیشن‌های Native

### 📱 طراحی Mobile-First
- رابط کاربری بهینه برای موبایل
- Bottom Navigation برای دسترسی آسان
- Touch-friendly interactions
- Pull to Refresh
- Safe Area support برای دستگاه‌های دارای Notch

## 📁 ساختار

```
Applications/PWA/
├── Controllers/
│   ├── AuthWebController.php
│   └── DashboardController.php
├── Helpers/
├── Resources/
│   ├── css/
│   │   └── app.css              # استایل‌های PWA با Mobile-First Approach
│   ├── js/
│   │   ├── app.js               # Entry Point اصلی
│   │   ├── pwa-init.js          # مدیریت Service Worker و PWA Features
│   │   └── ui-components.js     # کامپوننت‌های UI
│   └── views/
│       ├── layouts/
│       │   ├── base.blade.php   # Base Layout با PWA Meta Tags
│       │   └── app.blade.php    # App Layout با Bottom Nav
│       ├── auth/
│       │   └── login.blade.php
│       └── dashboard/
│           └── index.blade.php
├── PWAServiceProvider.php
└── routes.php
```

## 🔧 نصب و راه‌اندازی

### 1. تنظیمات Domain
در فایل `config/app.php` یا `.env`:

```php
'domains' => [
    'admin_panel' => env('ADMIN_PANEL_DOMAIN', 'admin.planexx.local'),
    'pwa' => env('PWA_DOMAIN', 'app.planexx.local'),
],
```

### 2. Vite Build
PWA به صورت خودکار توسط Vite شناسایی و build می‌شود:

```bash
npm run dev    # Development
npm run build  # Production
```

### 3. دسترسی
بعد از راه‌اندازی، PWA در آدرس زیر در دسترس است:
```
http://app.planexx.local
```

## 🎨 استایل‌ها

### CSS Variables
از CSS Variables مشترک پروژه استفاده می‌کند:
- `--color-primary`
- `--color-bg-primary`
- `--color-text-primary`
- و سایر متغیرها از `resources/css/variables.css`

### Tailwind CSS
تمام کلاس‌های Tailwind در دسترس است. همچنین کلاس‌های اختصاصی PWA:
- `.pwa-container` - Container با عرض مناسب
- `.pwa-header` - Header با Sticky positioning
- `.pwa-nav` - Bottom Navigation
- `.pwa-card` - کارت‌های استایل شده
- `.btn-pwa` - دکمه‌های بهینه شده برای تاچ

## 📱 Service Worker

### ثبت Service Worker
Service Worker به صورت خودکار در `pwa-init.js` ثبت می‌شود:

```javascript
pwaInit.registerServiceWorker();
```

### استراتژی‌های Caching
- **API Requests**: Network First (با fallback به cache)
- **Static Assets**: Cache First (CSS, JS, Images)
- **HTML Pages**: Network First (با offline page)

### Offline Support
فایل `/sw.js` تمام قابلیت‌های زیر را فراهم می‌کند:
- Pre-caching صفحات مهم
- Runtime caching برای درخواست‌های دیگر
- Offline fallback
- Background sync
- Push notifications

## 🔔 Push Notifications

### فعال‌سازی
```javascript
// درخواست مجوز برای اعلان‌ها
const permission = await Notification.requestPermission();

if (permission === 'granted') {
  // Subscribe to push notifications
}
```

### ارسال از Backend
```php
// در Backend می‌توانید از پکیج‌های Laravel برای ارسال استفاده کنید
```

## 🔄 Pull to Refresh

قابلیت Pull to Refresh به صورت خودکار فعال است:
```javascript
pwaInit.initPullToRefresh();
```

## 📦 Install Prompt

### نمایش دکمه نصب
```html
<div id="pwa-install-prompt" class="install-prompt">
  <button id="pwa-install-button">نصب اپلیکیشن</button>
</div>
```

منطق مدیریت در `pwa-init.js` پیاده‌سازی شده است.

## 🎯 Bottom Navigation

Navigation پایینی با آیکون‌ها:
```html
<nav class="pwa-nav">
  <a href="/dashboard" class="pwa-nav-item active">
    <i class="fas fa-home"></i>
    <span>خانه</span>
  </a>
  <!-- سایر آیتم‌ها -->
</nav>
```

## 🔐 Authentication

### Login
```php
Route::get('login', [AuthWebController::class, 'login']);
Route::post('auth', [AuthWebController::class, 'auth']);
```

### Logout
```php
Route::post('logout', [AuthWebController::class, 'logout']);
```

## 🛠️ Development

### اضافه کردن صفحه جدید

1. **Controller**:
```php
// Applications/PWA/Controllers/NewPageController.php
class NewPageController extends BaseWebController
{
    public function index(): View
    {
        return view('pwa::pages.new-page');
    }
}
```

2. **Route**:
```php
// Applications/PWA/routes.php
Route::get('new-page', [NewPageController::class, 'index'])->name('new-page');
```

3. **View**:
```blade
{{-- Applications/PWA/Resources/views/pages/new-page.blade.php --}}
<x-pwa::layouts.app title="صفحه جدید">
    <!-- محتوای صفحه -->
</x-pwa::layouts.app>
```

### اضافه کردن JavaScript مخصوص صفحه

```javascript
// Applications/PWA/Resources/js/pages/new-page.js
export function initNewPage() {
  // کدهای مخصوص این صفحه
}
```

سپس در blade:
```blade
@push('scripts')
    @vite('Applications/PWA/Resources/js/pages/new-page.js')
@endpush
```

## 🧪 Testing

### تست در مرورگر
1. مرورگر را در Developer Mode باز کنید
2. به تب Application بروید
3. Service Worker را بررسی کنید
4. Manifest را چک کنید
5. Offline mode را تست کنید

### تست در موبایل
1. PWA را روی یک سرور HTTPS deploy کنید
2. با موبایل وارد شوید
3. گزینه "Add to Home Screen" را بزنید
4. اپلیکیشن نصب شده را باز کنید

## 📚 Resources

### Manifest
`/public/manifest.json` - تنظیمات PWA

### Service Worker
`/public/sw.js` - Worker اصلی

### Icons
`/public/icons/` - آیکون‌های مختلف سایزها

## 🐛 Troubleshooting

### Service Worker ثبت نمی‌شود
- HTTPS استفاده کنید (یا localhost)
- Console را برای خطاها چک کنید
- Cache را clear کنید

### نصب نمیشود
- Manifest را بررسی کنید
- آیکون‌های مورد نیاز را اضافه کنید
- HTTPS الزامی است

### Offline کار نمی‌کند
- Service Worker فعال است؟
- استراتژی caching درست است؟
- Network را در DevTools چک کنید

## 🚀 بهینه‌سازی

### Performance
- از lazy loading برای تصاویر استفاده کنید
- JavaScript را به chunk های کوچک تقسیم کنید
- Critical CSS را inline کنید

### Bundle Size
- Dependencies غیرضروری را حذف کنید
- از Tree Shaking استفاده کنید
- Code Splitting را فعال کنید

## 📝 Best Practices

✅ **انجام دهید:**
- طراحی Mobile-First
- از Touch Events استفاده کنید
- Safe Area را رعایت کنید
- Offline Experience را فراهم کنید
- Loading States را نشان دهید

❌ **انجام ندهید:**
- Desktop-only UI components
- Mouse-only interactions
- بدون Error handling
- بدون Loading indicators
- Hover effects بدون Touch alternative
