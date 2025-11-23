# مهاجرت از CDN به پکیج‌های محلی

## تغییر CDN به پکیج‌های npm

برای بهبود عملکرد و کاهش وابستگی به شبکه خارجی، تمام CDNها به پکیج‌های npm منتقل شدند.

### پکیج‌های نصب شده:

#### Dependencies (production):
- `@fortawesome/fontawesome-free` - آیکون‌های Font Awesome
- `axios` - کتابخانه HTTP برای API calls

#### Dev Dependencies:
- `tailwindcss@^3.4.0` - فریمورک CSS
- `autoprefixer` - اضافه کردن vendor prefixes
- `postcss` - پردازش CSS

### تغییرات اعمال شده:

#### 1. Font Awesome
```css
/* قبل - CDN */
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

/* بعد - npm package */
@import '@fortawesome/fontawesome-free/css/all.min.css';
```

#### 2. Axios
```html
<!-- قبل - CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- بعد - ES6 import -->
import axios from 'axios';
```

#### 3. Sahel Font
```html
<!-- قبل - CDN -->
<link href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css" rel="stylesheet">

<!-- بعد - local files -->
<style>
@font-face {
    font-family: 'Sahel';
    src: url('/fonts/Sahel-Regular.woff2') format('woff2');
    font-weight: 400;
    font-display: swap;
}
</style>
```

### مزایای جدید:

1. **عدم وابستگی به CDN** - سرعت بالاتر و عدم وابستگی به شبکه
2. **بهینه‌سازی توسط Vite** - tree-shaking و minification
3. **مدیریت متمرکز** - تمام assets از طریق package.json مدیریت می‌شوند
4. **نسخه‌بندی بهتر** - کنترل دقیق نسخه‌های پکیج‌ها
5. **حجم bundle بهینه** - فقط کد استفاده شده در bundle نهایی قرار می‌گیرد

### فایل‌های اضافه شده:
- `public/fonts/` - فایل‌های فونت Sahel
- `postcss.config.js` - تنظیمات PostCSS
- `tailwind.config.js` - تنظیمات TailwindCSS

### فایل‌های تغییر یافته:
- `resources/css/app.css` - ایمپورت Font Awesome
- `resources/js/bootstrap.js` - ایمپورت Axios
- `app/Core/User/Resources/views/layout.blade.php` - حذف CDNها و اضافه کردن فونت محلی

حالا تمام assets به صورت محلی مدیریت می‌شوند و هیچ وابستگی به CDN وجود ندارد! 🎉
