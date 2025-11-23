# نصب و راه‌اندازی سیستم احراز هویت (نسخه 2.0)

## 📋 خلاصه فایل‌های ایجاد شده

### 1. Backend Files

#### Blade Views
```
app/Core/User/Resources/views/
└── auth.blade.php                 # صفحه ورود و تایید OTP
```

#### Controllers
```
app/Core/User/Http/Controllers/V1/Web/
└── AuthWebController.php          # کنترلر نمایش صفحه ورود
```

#### Routes
```
app/Core/User/Routes/V1/
├── web.php                        # مسیرهای وب برای احراز هویت
└── Admin/routes.php               # API routes
```

#### Configuration
```
config/
└── authService.php                # تنظیمات احراز هویت
```

### 2. Frontend - Core Architecture

```
resources/js/core/
├── index.js                       # مرکز دسترسی (Entry Point)
├── api-manager.js                 # مدیریت درخواست‌های HTTP
├── error-handler.js               # معالجه خطاها
├── state-manager.js               # مدیریت State
├── event-bus.js                   # سیستم رویداد‌ها
└── http-client.js                 # HTTP Client سطح بالا
```

### 3. Frontend - Modules

```
resources/js/modules/
├── auth.js                        # ماژول احراز هویت
├── form-handler.js                # مدیریت فرم‌ها
└── otp-auth-ui.js                 # رابط کاربری OTP
```

### 4. Frontend - Pages

```
resources/js/pages/
└── auth.js                        # صفحه احراز هویت
```

### 5. Layout & Views
```
resources/views/layouts/
└── auth.blade.php                 # Layout اصلی
```

### 6. Localization
```
app/Core/User/Resources/lang/fa/
└── auth.php                       # برچسب‌های فارسی
```

## 🔗 مسیرهای فعال

### مسیر نمایش صفحه
- **`GET /login`** → نمایش صفحه ورود
- کنترلر: `AuthWebController@login`
- View: `user::auth`

### مسیرهای API (موجود)
- **`GET /api/v1/admin/auth`** → شروع احراز هویت (ارسال کد OTP)
- **`POST /api/v1/admin/auth`** → تایید کد OTP و ورود
- **`GET /api/v1/admin/auth/logout`** → خروج از سیستم

## 🚀 شروع سریع

### 1. نصب Dependencies

```bash
# وابستگی‌های NPM موجود هستند
npm install
```

### 2. Compile Assets

```bash
npm run dev
```

### 3. بازدید صفحه

```
http://localhost:8000/login
```

### 4. تست Flow

```
1. شماره موبایل: 09123456789
2. کد OTP: 1111 (یا کد دریافتی)
3. ورود موفق و redirect به dashboard
```

## 🔍 جزئیات فایل‌ها

### `auth.blade.php`
- فرم شماره موبایل (مرحله 1)
- فرم کد OTP (مرحله 2)
- دکمه‌های بازگشت و ارسال مجدد
- لینک توافق قوانین
- استفاده از Tailwind CSS

**ویژگی‌ها:**
- ✅ دو مرحله‌ای
- ✅ Auto-submit بعد از ۴ رقم OTP
- ✅ تایمر ۶۰ ثانیه برای ارسال مجدد
- ✅ Paste support برای کد OTP
- ✅ موبایل‌friendly

### `public/js/auth/user.js`
منطق کامل احراز هویت با Axios:

**Functions:**
- `init()` - مقداردهی اولیه
- `initMobileForm()` - مدیریت فرم موبایل
- `initiateAuth(mobile)` - فراخوانی API برای ارسال کد
- `showOTPStep(mobile)` - ظاهر کردن مرحله OTP
- `initOTPInputs()` - مدیریت input‌های OTP
- `checkOTPComplete()` - چک کردن تکمیل OTP
- `submitOTP(otp)` - ارسال OTP به API
- `initBackButton()` - دکمه بازگشت
- `startResendTimer()` - شروع تایمر ارسال مجدد
- `handleError(error)` - مدیریت خطاها

### `public/js/auth/utils.js`
توابع کمکی:
- `validateMobile(mobile)` - تحقق شماره موبایل ایرانی
- `validateOTP(otp)` - تحقق کد ۴ رقمی
- `showToast(message, type, duration)` - نمایش نوتیفکیشن
- `debounce(func, wait)` - جلوگیری از فراخوانی مکرر

### `AuthWebController.php`
```php
class AuthWebController extends BaseWebController
{
    public function login(): View
    {
        return view('auth');
    }
}
```

فقط یک متد برای نمایش صفحه. تمام درخواست‌های API از طریق Axios انجام می‌شود.

### `auth.php` (Localization)
```php
return [
    'login_title' => 'ورود به سیستم',
    'login_to_planexx' => 'ورود به پلنکس',
    'enter_mobile_number' => 'لطفاً شماره موبایل خود را وارد کنید',
    // ... سایر برچسب‌ها
];
```

## 🚀 نحوه استفاده

### 1. دسترسی به صفحه ورود
```
http://localhost:8000/login
```

### 2. وارد کردن شماره موبایل
```
09123456789
```

### 3. دریافت کد OTP
JavaScript فراخوانی می‌کند: `POST /api/v1/admin/auth/initiate`

### 4. وارد کردن کد OTP
هر رقم در یک input جداگانه. Auto-submit بعد از ۴ رقم.

### 5. ورود موفق
- Token ذخیره در localStorage
- Redirect به `/admin/dashboard`

## 🔐 ایمنی

✅ CSRF Token خودکار
✅ Rate Limiting روی API
✅ Validation در هر دو طرف (Client + Server)
✅ Token-based authentication
✅ Secure localStorage برای Token

## 🎨 سفارشی‌سازی

### تغییر رنگ‌ها
در `resources/views/layouts/auth.blade.php`:
```javascript
colors: {
    primary: '#0f172a',  // تغییر این
    'text-primary': '#0f172a',
    // ...
}
```

### تغییر متن‌ها
در `app/Core/User/Resources/lang/fa/auth.php`:
```php
'login_title' => 'متن جدید',
```

### تغییر Redirect پس از ورود
در `public/js/auth/user.js` سطر 148:
```javascript
window.location.href = '/custom/path';
```

## 📦 Dependencies

- **Laravel 12+**
- **Axios** (CDN)
- **Tailwind CSS 3+** (CDN)
- **Font Awesome 6.5.1** (CDN)
- **Sahel Font 3.4.0** (CDN)

## ✅ Checklist

- [x] فایل‌های Blade ایجاد شده
- [x] Controllers ایجاد شده
- [x] Routes ثبت شده
- [x] CSS و JS آماده
- [x] Layout ایجاد شده
- [x] Localization تعریف شده
- [x] Axios مقداردهی شده
- [x] CSRF Token پیکربندی شده
- [x] API integration آماده

## 📚 فایل‌های مرجع

- `app/Core/User/Resources/views/README.md` - معماری سیستم
- `app/Core/User/Resources/views/auth.blade.php` - صفحه احراز هویت
- `public/js/auth/user.js` - منطق JavaScript

## 🆘 عیب‌یابی

### صفحه تحت CSS بدون استایل باز می‌شود
**حل**: اطمینان حاصل کنید که CDN‌ها در layout بارگذاری شده‌اند

### درخواست‌های Axios ناموفق هستند
**حل**: بررسی کنید که API endpoints در `public/js/auth/user.js` صحیح هستند

### Token ذخیره نمی‌شود
**حل**: بررسی کنید که localStorage فعال است (نه در Incognito)

---

**آخرین بروزرسانی**: 2025-11-20
