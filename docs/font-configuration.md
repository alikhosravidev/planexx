# Font Configuration - Sahel Font

## 📝 Overview

پروژه از فونت **Sahel** استفاده می‌کند که یک فونت فارسی متن‌باز است. فایل‌های فونت در `public/fonts/` قرار دارند.

## ⚠️ Vite Build Warnings - آنها Safe هستند

هنگام build شاید این warning را ببینید:

```
/fonts/Sahel-Regular.woff2 didn't resolve at build time,
will remain unchanged to be resolved at runtime
```

### این warning:
- ✅ **Safe است** - فایل‌ها موجود هستند
- ✅ **Normal است** - برای static assets معمول است
- ✅ **Runtime resolve می‌شود** - خودکار کار می‌کند
- ✅ **No functionality impact** - فونت‌ها به خوبی بارگذاری می‌شوند

### چرا happens:

1. فونت‌ها در `public/` هستند (static assets)
2. Vite فایل‌های static را transform نمی‌کند
3. Build-time resolution نیاز ندارد (runtime resolve می‌شود)
4. اینجا informational warning است، نه error

## 📂 File Structure

```
public/
└── fonts/
    ├── Sahel-Regular.woff2      # Weight 400
    ├── Sahel-SemiBold.woff2     # Weight 600
    └── Sahel-Bold.woff2         # Weight 700

resources/
└── fonts/
    └── fonts.css                # Font-face definitions

resources/css/
└── app.css                      # Main CSS (imports fonts.css)
```

## 🎨 Usage

فونت Sahel خودکار برای تمام elements استفاده می‌شود:

```css
body {
    font-family: 'Sahel', sans-serif;
}
```

### Font Weights:

```css
/* Regular (400) */
body { font-weight: 400; }

/* SemiBold (600) */
strong, b { font-weight: 600; }

/* Bold (700) */
h1, h2, h3 { font-weight: 700; }
```

## ✅ Build Output

Build موفق است:
```
✓ 72 modules transformed
✓ built in 3.56s
```

Warnings بخش مطبوع build process هستند.

## 🔍 Font Loading Check

اگر می‌خواهید بررسی کنید که فونت‌ها درست load می‌شوند:

### Browser Console:
```javascript
// Check if Sahel font is loaded
const loaded = document.fonts.check('16px Sahel');
console.log('Sahel font loaded:', loaded);

// Get all loaded fonts
for (const font of document.fonts) {
    console.log(font.family);
}
```

### Chrome DevTools:
1. F12 → Network tab
2. Filter: `fonts/Sahel`
3. شما باید 3 فایل .woff2 را ببینید

## 🚀 Performance

- **Format**: WOFF2 (بهترین compression)
- **Size**: ~15KB per font (compressed)
- **Loading**: Parallel (all at once)
- **font-display**: swap (text visible immediately)

## 🎯 Fallback Fonts

اگر Sahel load نشود:

```css
font-family: 'Sahel', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
```

## 📱 Mobile Support

WOFF2 format توسط تمام modern browsers supported است:
- ✅ Chrome/Edge 36+
- ✅ Firefox 39+
- ✅ Safari 11+
- ✅ iOS Safari 11+
- ✅ Android 5+

## 🔧 Customization

### تغییر فونت:

1. فایل‌های فونت جدید را به `public/fonts/` اضافه کنید
2. تعریف‌ها را در `resources/fonts/fonts.css` update کنید:

```css
@font-face {
    font-family: 'NewFont';
    src: url('/fonts/NewFont-Regular.woff2') format('woff2');
    font-weight: 400;
    font-display: swap;
}
```

3. `resources/css/app.css` میں استفاده کریں:

```css
body {
    font-family: 'NewFont', sans-serif;
}
```

### هر weight کے لیے الگ font:

```css
@font-face {
    font-family: 'MyFont-Regular';
    src: url('/fonts/MyFont-Regular.woff2');
    font-weight: normal;
}

@font-face {
    font-family: 'MyFont-Bold';
    src: url('/fonts/MyFont-Bold.woff2');
    font-weight: bold;
}

body {
    font-family: 'MyFont-Regular', sans-serif;
}

strong {
    font-family: 'MyFont-Bold', sans-serif;
    font-weight: normal;
}
```

## 🧪 Testing

### Font Loading Test:
```html
<div style="font-family: 'Sahel', sans-serif;">
    سلام دنیا - Hello World
</div>
```

اگر متن فارسی درست نمایش داده شود، فونت loaded است.

## 📚 Resources

- [Sahel Font GitHub](https://github.com/rastikerdar/sahel)
- [WOFF2 Specification](https://www.w3.org/TR/WOFF2/)
- [Font Loading Best Practices](https://web.dev/performance-web-fonts/)

## 🎓 FAQ

### Q: چرا warning می‌بینم؟
**A**: یہ normal ہے۔ Vite informational warning ہے۔ Fonts runtime میں resolve ہو رہے ہیں۔

### Q: فونت load نہیں ہو رہا؟
**A**: Check کریں:
1. `public/fonts/` میں files ہیں؟
2. Network tab میں fonts load ہو رہے ہیں؟
3. CSS میں صحیح path ہے؟

### Q: Custom font کیسے add کروں؟
**A**: اوپر "Customization" section دیکھیں۔

### Q: کون سے browsers support کرتے ہیں؟
**A**: WOFF2 تمام modern browsers میں supported ہے۔
