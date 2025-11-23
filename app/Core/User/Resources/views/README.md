# سیستم احراز هویت پلنکس - نسخه Laravel

## 🎯 معرفی

این پوشه شامل تمام فایل‌های مربوط به سیستم احراز هویت (Login/OTP) برای پنل ادمین است. سیستم بر اساس معماری API-FIRST طراحی شده و از Axios برای تمام درخواست‌های API استفاده می‌کند.

## 📁 ساختار فایل‌ها

```
app/Core/User/Resources/
├── views/
│   ├── auth.blade.php          # صفحه ورود و تایید کد OTP
│   └── README.md               # این فایل
│
├── lang/
│   └── fa/
│       └── auth.php            # برچسب‌های فارسی صفحه ورود
│
└── [assets move to public/]
```

## 🔄 معماری سیستم

### مسیر درخواست:

1. **صفحه نمایش (View)**
   - فایل: `auth.blade.php`
   - موقعیت: `app/Core/User/Resources/views/`
   - وظیفه: نمایش فرم‌های ورود و کد OTP

2. **کنترلر وب (Web Controller)**
   - فایل: `AuthWebController.php`
   - موقعیت: `app/Core/User/Http/Controllers/V1/Web/`
   - وظیفه: تنها نمایش صفحات (view rendering)
   - API calls: ❌ نه (صفحه مسئول است)

3. **کنترلر API**
   - فایل: `AuthController.php`
   - موقعیت: `app/Core/User/Http/Controllers/V1/Admin/`
   - وظیفه: پردازش درخواست‌های API
   - Endpoints:
     - `POST /api/v1/admin/auth/initiate` - شروع احراز هویت
     - `POST /api/v1/admin/auth` - تایید کد OTP
     - `POST /api/v1/admin/auth/initiate-reset-password`
     - `POST /api/v1/admin/auth/reset-password`
     - `POST /api/v1/admin/auth/logout`

4. **JavaScript (Frontend)**
   - فایل: `public/js/auth/user.js`
   - وظیفه: مدیریت فرم‌ها و تماس‌های API با Axios
   - ✅ تمام درخواست‌های API

## 🚀 جریان کار احراز هویت

```
┌─────────────────────────────────────────────────────────────┐
│ 1. صفحه ورود (auth.blade.php)                              │
│    - نمایش فرم شماره موبایل                                │
│    - فوکوس روی input موبایل                               │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. JavaScript Event Handler (user.js)                        │
│    - تحقق از اعتبار شماره موبایل                           │
│    - Axios POST /api/v1/admin/auth/initiate                 │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. API Controller (AuthController.php)                      │
│    - پردازش درخواست                                       │
│    - ارسال کد OTP                                          │
│    - بازگشت پاسخ JSON                                      │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 4. JavaScript Toast (نوتیفکیشن)                            │
│    - نمایش پیام موفقیت                                     │
│    - ظاهر کردن مرحله OTP                                  │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 5. کد OTP Input                                            │
│    - فوکوس خودکار به input بعدی                           │
│    - Paste Support                                         │
│    - Auto-submit بعد از ۴ رقم                              │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 6. ارسال OTP                                               │
│    - Axios POST /api/v1/admin/auth                          │
│    - ارسال identifier + password (OTP)                      │
└──────────────────┬──────────────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────────────┐
│ 7. تایید و نمایش Dashboard                                 │
│    - ذخیره Token در localStorage                           │
│    - Redirect به /admin/dashboard                          │
└─────────────────────────────────────────────────────────────┘
```

## 📝 فایل‌های مربوطه

### نمایش (Views)
- **`auth.blade.php`** - صفحه احراز هویت اصلی
  - فرم شماره موبایل (مرحله 1)
  - فرم کد OTP (مرحله 2)
  - دکمه‌های بازگشت و ارسال مجدد

### کنترلرها (Controllers)
- **`AuthWebController.php`** (Web)
  - متد `login()` - نمایش صفحه ورود

- **`AuthController.php`** (API)
  - متد `initiateAuth()` - شروع احراز هویت
  - متد `auth()` - تایید کد OTP
  - متد `logout()` - خروج

### JavaScript
- **`public/js/auth/utils.js`**
  - `validateMobile()` - تحقق شماره موبایل
  - `validateOTP()` - تحقق کد OTP
  - `showToast()` - نمایش نوتیفکیشن

- **`public/js/auth/user.js`**
  - تمام منطق احراز هویت
  - مدیریت درخواست‌های API با Axios

### استایل‌ها (Styles)
- **`public/css/auth/variables.css`** - متغیرهای رنگ و طراحی
- **`public/css/auth/app.css`** - استایل‌های Global

### ترجمه (Localization)
- **`app/Core/User/Resources/lang/fa/auth.php`** - برچسب‌های فارسی

## 🔧 نحوه استفاده

### صرفاً نمایش صفحه
```php
// در مسیرهای وب
Route::get('login', [AuthWebController::class, 'login'])->name('login');
```

### تعریف شماره موبایل
شماره موبایل باید در فرمت `09xxxxxxxxx` باشد (۱۱ رقم).

### دریافت کد OTP
کد OTP یک عدد ۴ رقمی است که پس از ارسال شماره موبایل، درخواست API `initiateAuth` کد را به شماره ارسال می‌کند.

### ذخیره Token
پس از تایید موفق کد OTP، Token در `localStorage` تحت کلید `token` ذخیره می‌شود.

## 🎨 سفارشی‌سازی

### تغییر رنگ‌ها
رنگ‌های استفاده شده در:
- **`resources/views/layouts/auth.blade.php`** - تنظیمات Tailwind
- **`public/css/auth/variables.css`** - متغیرهای CSS

رنگ اصلی:
- `primary: #0f172a` (سیاه پیشفرض)
- `bg-secondary: #f8fafc` (پس‌زمینه سفید

 با تون)

### تغییر متن‌ها
تمام متن‌های صفحه از ترجمه‌ها می‌آید:
```php
// app/Core/User/Resources/lang/fa/auth.php
return [
    'login_title' => 'ورود به سیستم',
    'login_to_planexx' => 'ورود به پلنکس',
    // ...
];
```

در `auth.blade.php` استفاده می‌شود:
```blade
<h1>{{ trans('user::auth.login_to_planexx') }}</h1>
```

### تغییر Redirect پس از ورود
در `public/js/auth/user.js`:
```javascript
// سطر 146
window.location.href = '/admin/dashboard';  // تغییر این مسیر
```

## 🛠️ توسعه و داینامیک‌سازی

### اضافه کردن فیلد جدید
1. اضافه کنید به `auth.blade.php`
2. اضافه کنید به `public/js/auth/user.js` (برای مدیریت)
3. اضافه کنید به API request

### افزایش ایمنی
- تغییر `initiateAuth` از GET به POST
- اضافه کردن Rate Limiting
- استفاده از CSRF Token

## 📚 منابع

- **Laravel Blade**: https://laravel.com/docs/blade
- **Tailwind CSS**: https://tailwindcss.com
- **Axios**: https://axios-http.com
- **Font Awesome**: https://fontawesome.com

## ✅ نکات مهم

1. ✅ تمام درخواست‌های API از طریق Axios انجام می‌شود
2. ✅ کنترلر Web تنها نمایش صفحات را مسئول است
3. ✅ CSRF Token خودکار اضافه می‌شود
4. ✅ Token پس از ورود موفق ذخیره می‌شود
5. ✅ تمام متن‌های صفحه سفارشی‌پذیر هستند

---

**نسخه**: 1.0.0
**تاریخ آخرین بروزرسانی**: 2025-11-20
