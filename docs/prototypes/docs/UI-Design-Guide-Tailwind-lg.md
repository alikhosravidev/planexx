# دستورالعمل طراحی رابط کاربری - سبک مدرن و مینیمال (Tailwind CSS)

## 🎯 فلسفه اصلی

این دستورالعمل بر پایه **Tailwind CSS** طراحی شده تا طراحی **مینیمال، مدرن و حرفه ای** با **نگهداری آسان** و **یکپارچگی کامل** ایجاد کنید. از کدنویسی CSS اختصاصی **فقط در موارد ضروری** استفاده می شود.

---

## 📦 پیش نیازها و CDN های اصلی

### 1️⃣ Tailwind CSS (الزامی)
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### 2️⃣ فونت Sahel (برای همه متون فارسی)
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css">
```

### 3️⃣ Font Awesome (برای آیکون ها)
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

### ⚙️ پیکربندی Tailwind و متغیرهای سفارشی
```html
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#0f172a',
          secondary: '#64748b',
          'text-primary': '#0f172a',
          'text-secondary': '#475569',
          'text-muted': '#64748b',
          'bg-primary': '#ffffff',
          'bg-secondary': '#f8fafc',
          'bg-tertiary': '#fafbfc',
          'bg-label': '#f8fafc',
          'border-light': '#f1f5f9',
          'border-medium': '#e2e8f0',
          'border-dark': '#cbd5e1',
        },
        spacing: {
          'xs': '4px',
          'sm': '8px',
          'md': '12px',
          'lg': '16px',
          'xl': '20px',
          '2xl': '24px',
          '3xl': '32px',
          '4xl': '40px',
          '5xl': '80px',
        },
        borderRadius: {
          'sm': '6px',
          'md': '8px',
          'lg': '10px',
          'xl': '12px',
          '2xl': '16px',
          '3xl': '20px',
        },
        fontSize: {
          'xs': '13px',
          'sm': '14px',
          'base': '15px',
          'md': '16px',
          'lg': '18px',
          'xl': '20px',
          '2xl': '24px',
          '3xl': '30px',
          '4xl': '36px',
        },
        lineHeight: {
          'tight': '1.25',
          'snug': '1.375',
          'normal': '1.5',
          'relaxed': '1.625',
          'loose': '1.75',
        },
        boxShadow: {
          'sm': '0 1px 3px rgba(0,0,0,0.04)',
          'md': '0 4px 16px rgba(0,0,0,0.06)',
          'lg': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
          'focus': '0 0 0 3px rgba(15, 23, 42, 0.05)',
          'button': '0 4px 12px rgba(15, 23, 42, 0.15)',
        }
      }
    }
  }
</script>

<style>
  /* فونت Sahel برای همه عناصر به جز آیکون ها */
  body, button, input, textarea, select, p, div, span, h1, h2, h3, h4, h5, h6 {
    font-family: 'Sahel', sans-serif !important;
  }
  
  /* آیکون های Font Awesome نباید فونت Sahel داشته باشند */
  .fa, .fas, .far, .fal, .fab {
    font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Pro', 'Font Awesome 6 Brands' !important;
  }
  
  /* متغیرهای CSS برای موارد ضروری */
  :root {
    --transition-fast: all 0.2s ease;
    --transition-normal: all 0.3s ease;
  }
</style>
```

---

## 🎨 پالت رنگی Tailwind

---

## ✍️ راهنمای کامل تایپوگرافی

این بخش استانداردهای تایپوگرافی برای خوانایی بهینه را بر اساس best practices رابط‌های کاربری مدرن ارائه می‌دهد.

### 📏 سلسله مراتب اندازه‌ها

| کلاس | اندازه | کاربرد | Line Height |
|------|--------|--------|-------------|
| `text-xs` | 13px | Badge، Label کوچک، Meta text | `leading-normal` |
| `text-sm` | 14px | Label فرم‌ها، Caption، Table header | `leading-normal` |
| `text-base` | **15px** | **متن اصلی، Input، Button** | `leading-normal` |
| `text-md` | **16px** | متن اصلی در پاراگراف‌های طولانی | `leading-relaxed` |
| `text-lg` | 18px | Subtitle، پاراگراف مهم | `leading-relaxed` |
| `text-xl` | 20px | عنوان کارت، Section title | `leading-snug` |
| `text-2xl` | 24px | عنوان صفحه، Main heading | `leading-snug` |
| `text-3xl` | 30px | عنوان اصلی لندینگ | `leading-tight` |
| `text-4xl` | 36px | Hero heading | `leading-tight` |

### 🎯 کاربردهای استاندارد

#### متن‌های اصلی (Body Text)
```html
<!-- متن عادی - استاندارد -->
<p class="text-base text-text-primary leading-normal">
  این متن اصلی با اندازه 15px است که برای اکثر محتوا مناسب است.
</p>

<!-- متن طولانی - راحت‌تر برای خواندن -->
<p class="text-md text-text-primary leading-relaxed">
  برای پاراگراف‌های طولانی‌تر از این اندازه استفاده کنید تا خوانایی بهتر باشد.
</p>

<!-- متن کمکی -->
<p class="text-sm text-text-secondary leading-normal">
  متن کمکی یا توضیحات اضافی
</p>
```

#### عناوین (Headings)
```html
<!-- عنوان اصلی صفحه -->
<h1 class="text-2xl font-bold text-text-primary leading-snug mb-4">
  عنوان اصلی صفحه
</h1>

<!-- عنوان بخش -->
<h2 class="text-xl font-semibold text-text-primary leading-snug mb-3">
  عنوان بخش
</h2>

<!-- عنوان زیربخش -->
<h3 class="text-lg font-semibold text-text-primary leading-snug mb-2">
  عنوان زیربخش
</h3>
```

#### فرم‌ها (Forms)
```html
<!-- Label فرم -->
<label class="text-sm text-text-secondary leading-normal">
  نام کاربری
</label>

<!-- Input -->
<input class="text-base text-text-primary leading-normal" />

<!-- متن کمکی زیر فرم -->
<span class="text-xs text-text-muted leading-normal">
  حداقل 8 کاراکتر
</span>
```

#### دکمه‌ها (Buttons)
```html
<!-- دکمه استاندارد -->
<button class="text-base font-medium leading-normal">
  ذخیره تغییرات
</button>

<!-- دکمه کوچک -->
<button class="text-sm font-medium leading-normal">
  ویرایش
</button>
```

### 📊 Line Height (فاصله خطوط)

| کلاس | نسبت | کاربرد |
|------|------|--------|
| `leading-tight` | 1.25 | عناوین بزرگ (Hero) |
| `leading-snug` | 1.375 | عناوین و تیترها |
| `leading-normal` | 1.5 | متن اصلی، فرم‌ها، دکمه‌ها |
| `leading-relaxed` | 1.625 | پاراگراف‌های طولانی |
| `leading-loose` | 1.75 | محتوای خواناتر با فاصله زیاد |

### 💡 نکات مهم تایپوگرافی

1. **متن اصلی**: همیشه حداقل `15px` (`text-base`) استفاده کنید
2. **Line Height**: برای متن‌های فارسی، `leading-normal` یا بالاتر توصیه می‌شود
3. **Label فرم‌ها**: `text-sm` (14px) کافی است اما نه کمتر
4. **Contrast**: متن اصلی `text-text-primary`، توضیحات `text-text-secondary`
5. **فاصله بین پاراگراف‌ها**: از `space-y-4` یا `space-y-6` استفاده کنید

### 🎨 ترکیب‌های توصیه شده

```html
<!-- کارت با تایپوگرافی صحیح -->
<div class="bg-white rounded-2xl p-6">
  <h3 class="text-xl font-semibold text-text-primary leading-snug mb-3">
    عنوان کارت
  </h3>
  <p class="text-base text-text-primary leading-relaxed mb-4">
    این یک پاراگراف نمونه است که با اندازه و فاصله خط مناسب نوشته شده.
  </p>
  <span class="text-sm text-text-secondary leading-normal">
    آخرین به‌روزرسانی: امروز
  </span>
</div>
```

---

## 📊 اعداد و تاریخ فارسی

چون اکثر صفحات **فارسی** هستند، **همیشه** از ارقام فارسی و تاریخ شمسی استفاده کنید.

### ✅ ارقام فارسی (الزامی)
```html
<!-- آمار -->
<div class="text-3xl font-bold">۱,۲۳۴</div>

<!-- Badge -->
<span class="...">۵ پیام جدید</span>

<!-- تاریخ -->
<p class="text-sm text-text-secondary">۱۲ شهریور ۱۴۰۴</p>
```

### 📅 تاریخ شمسی
فرمت استاندارد: **روز ماه سال** (مثال: ۱۵ آذر ۱۴۰۳)  
ماه‌ها: فروردین، اردیبهشت، خرداد، تیر، مرداد، شهریور، مهر، آبان، آذر، دی، بهمن، اسفند

### ⚠️ نکات مهم
- ✅ همیشه ارقام فارسی (۰۱۲۳۴۵۶۷۸۹) در محتوای فارسی
- ✅ تاریخ شمسی با فرمت: **روز ماه سال**
- ✅ کپی رایتینگ دوستانه و واضح
- ❌ هرگز اعداد انگلیسی یا تاریخ میلادی در رابط فارسی

---

## 🎨 پالت رنگی Tailwind

### رنگ های اصلی (به جای مقادیر hex)
- **اصلی:** `bg-primary` `text-primary`
- **ثانویه:** `bg-secondary` `text-secondary`
- **متن کم رنگ:** `text-text-muted`
- **پس زمینه ها:** `bg-bg-primary`, `bg-bg-secondary`, `bg-bg-tertiary`
- **Border:** `border-border-light`, `border-border-medium`, `border-border-dark`

### رنگ های وضعیت
```html
<!-- Success -->
<div class="bg-green-50 text-green-800 border border-green-200"></div>

<!-- Danger -->
<div class="bg-red-50 text-red-600 border border-red-200"></div>

<!-- Warning -->
<div class="bg-yellow-50 text-yellow-800 border border-yellow-200"></div>

<!-- Info -->
<div class="bg-sky-50 text-sky-700 border border-sky-200"></div>
```

---

## 🏗️ ساختار و Layout

### Container اصلی
```html
<div class="max-w-[1400px] mx-auto px-8 py-5xl">
  <!-- محتوا -->
</div>
```

### Grid System
```html
<!-- دو ستونه -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-3xl">
  <!-- کارت ها -->
</div>

<!-- سه ستونه -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3xl">
  <!-- کارت ها -->
</div>
```

---

## 🃏 کارت ها و کانتینرها

### کارت استاندارد
```html
<div class="bg-bg-primary border border-border-light rounded-3xl p-3xl shadow-sm hover:shadow-md transition-all duration-200">
  <!-- محتوای کارت -->
</div>
```

### کارت با Header
```html
<div class="bg-bg-primary border border-border-light rounded-3xl shadow-sm overflow-hidden">
  <!-- Header -->
  <div class="px-3xl py-xl border-b border-border-light">
    <h3 class="text-xl font-semibold text-text-primary tracking-tight leading-snug">عنوان کارت</h3>
  </div>
  
  <!-- Body -->
  <div class="p-3xl">
    <!-- محتوا -->
  </div>
</div>
```

---

## 🔘 دکمه ها

### دکمه اصلی (Primary)
```html
<button class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
  <i class="fa-solid fa-plus"></i>
  <span>افزودن آیتم</span>
</button>
```

### دکمه ثانویه (Secondary)
```html
<button class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
  انصراف
</button>
```

### دکمه خطر (Danger)
```html
<button class="bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal">
  حذف
</button>
```

### دکمه موفقیت (Success)
```html
<button class="bg-green-600 text-white px-xl py-md rounded-lg font-medium hover:bg-green-700 transition-all duration-200 text-base leading-normal">
  ذخیره
</button>
```

### ⚠️ نکته مهم برای آیکون ها در دکمه های فارسی
```html
<!-- درست ✅ - آیکون در سمت راست با ml-2 -->
<button class="...">
  <i class="fa-solid fa-plus ml-2"></i>
  <span>افزودن</span>
</button>

<!-- غلط ❌ - بدون فاصله -->
<button class="...">
  <i class="fa-solid fa-plus"></i>
  <span>افزودن</span>
</button>
```

---

## 📝 Input Groups (سبک منحصر به فرد)

### Input با Label چسبیده
```html
<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
  <div class="flex items-stretch">
    <!-- Label -->
    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
      نام کاربری
    </label>
    
    <!-- Input -->
    <input type="text" 
           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
           placeholder="نام کاربری خود را وارد کنید">
  </div>
</div>
```

### Textarea با Label
```html
<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
  <div class="flex">
    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
      توضیحات
    </label>
    <textarea rows="4" 
              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
              placeholder="توضیحات خود را وارد کنید"></textarea>
  </div>
</div>
```

> **⚠️ نکته مهم:** برای Textarea از `flex` بدون `items-start` یا `items-stretch` استفاده کنید تا Label به طور خودکار تمام ارتفاع Textarea را بگیرد.


### Select با Label
```html
<div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
  <div class="flex items-stretch">
    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
      دسته بندی
    </label>
    <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
      <option>انتخاب کنید</option>
      <option>گزینه ۱</option>
      <option>گزینه ۲</option>
    </select>
  </div>
</div>
```

---

## 🎚️ Radio Buttons (برای گزینه های کم)

### Radio Group افقی
```html
<div class="flex items-center gap-2xl">
  <label class="flex items-center gap-2 cursor-pointer">
    <input type="radio" name="status" value="active" class="w-4 h-4 text-primary accent-primary">
    <span class="text-base text-text-primary leading-normal">فعال</span>
  </label>
  
  <label class="flex items-center gap-2 cursor-pointer">
    <input type="radio" name="status" value="inactive" class="w-4 h-4 text-primary accent-primary">
    <span class="text-base text-text-primary leading-normal">غیرفعال</span>
  </label>
</div>
```

---

## 🔘 Toggle Switch (سوئیچ روشن/خاموش)

> **⚠️ نکته بسیار مهم برای RTL:** کلاس‌های استاندارد Tailwind برای Toggle در حالت RTL به درستی کار نمی‌کنند. از ساختار زیر استفاده کنید.

### Toggle با JavaScript (توصیه شده برای RTL)

این روش برای پروژه‌های فارسی RTL کاملاً سازگار است:

```html
<!-- HTML Structure -->
<label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitch(this)">
  <input type="checkbox" class="sr-only">
  <!-- Track - حالت غیرفعال -->
  <div class="w-11 h-6 rounded-full transition-colors bg-gray-200">
    <!-- Knob - دکمه گرد -->
    <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 mr-[22px]"></span>
  </div>
</label>

<!-- حالت فعال: bg-slate-900 و mr-0.5 -->
<label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitch(this)">
  <input type="checkbox" class="sr-only" checked>
  <div class="w-11 h-6 rounded-full transition-colors bg-slate-900">
    <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 mr-0.5"></span>
  </div>
</label>
```

```javascript
// JavaScript برای تغییر وضعیت Toggle
function toggleSwitch(label) {
  const input = label.querySelector('input');
  const track = label.querySelector('div');
  const knob = label.querySelector('span');
  
  input.checked = !input.checked;
  
  if (input.checked) {
    // حالت فعال
    track.classList.remove('bg-gray-200');
    track.classList.add('bg-slate-900');
    knob.classList.remove('mr-[22px]');
    knob.classList.add('mr-0.5');
  } else {
    // حالت غیرفعال
    track.classList.remove('bg-slate-900');
    track.classList.add('bg-gray-200');
    knob.classList.remove('mr-0.5');
    knob.classList.add('mr-[22px]');
  }
}
```

### Toggle در یک ردیف با Label

```html
<div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl">
  <div class="flex items-center gap-3">
    <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center">
      <i class="fa-solid fa-bell text-slate-500 text-sm"></i>
    </div>
    <div>
      <p class="text-slate-900 text-sm font-medium">اعلان‌ها</p>
      <p class="text-slate-500 text-xs">دریافت اعلان‌های اپلیکیشن</p>
    </div>
  </div>
  
  <!-- Toggle -->
  <label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitch(this)">
    <input type="checkbox" class="sr-only" checked>
    <div class="w-11 h-6 rounded-full transition-colors bg-slate-900">
      <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 mr-0.5"></span>
    </div>
  </label>
</div>
```

### Toggle با رنگ‌های مختلف

```html
<!-- Toggle سبز (Success) -->
<label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitchGreen(this)">
  <input type="checkbox" class="sr-only" checked>
  <div class="w-11 h-6 rounded-full transition-colors bg-green-600">
    <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 mr-0.5"></span>
  </div>
</label>

<!-- Toggle آبی (Info) -->
<label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitchBlue(this)">
  <input type="checkbox" class="sr-only" checked>
  <div class="w-11 h-6 rounded-full transition-colors bg-blue-600">
    <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 mr-0.5"></span>
  </div>
</label>
```

### ❌ روش‌هایی که در RTL کار نمی‌کنند

```html
<!-- ❌ غلط - peer-checked با translate در RTL مشکل دارد -->
<label class="relative inline-flex items-center cursor-pointer">
  <input type="checkbox" class="sr-only peer">
  <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-slate-900 after:content-[''] after:absolute after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
</label>

<!-- ❌ غلط - start/end classes در RTL ممکن است مشکل ایجاد کند -->
<div class="... after:start-[2px] peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"></div>
```

### ✅ قوانین Toggle برای RTL

1. **همیشه از JavaScript** برای تغییر وضعیت استفاده کنید
2. **از `mr-[22px]` و `mr-0.5`** برای جابجایی دکمه استفاده کنید (نه translate)
3. **کلاس `flex-shrink-0`** را به label اضافه کنید تا Toggle فشرده نشود
4. **از `transition-transform duration-200`** برای انیمیشن نرم استفاده کنید
5. **هرگز از `peer-checked:after:translate-x`** در RTL استفاده نکنید

---

## 🏷️ Badge ها

### Badge منو
```html
<span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium uppercase tracking-wide">
  <i class="fa-solid fa-bars"></i>
  منو
</span>
```

### Badge لینک
```html
<span class="inline-flex items-center gap-1.5 bg-sky-50 text-sky-700 px-2.5 py-1 rounded-lg text-xs font-medium uppercase tracking-wide">
  <i class="fa-solid fa-link"></i>
  لینک
</span>
```

### Badge متن
```html
<span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg text-xs font-medium uppercase tracking-wide">
  <i class="fa-solid fa-text"></i>
  متن
</span>
```

---

## 🎯 آیکون ها

### اندازه ها
```html
<!-- آیکون اصلی - 24x24 -->
<i class="fa-solid fa-home text-2xl text-text-muted"></i>

<!-- آیکون کوچک - 20x20 -->
<i class="fa-solid fa-angle-left text-xl text-text-muted"></i>

<!-- آیکون زیرمنو با پس زمینه -->
<div class="w-5 h-5 bg-border-light rounded flex items-center justify-center">
  <i class="fa-solid fa-chevron-left text-xs text-text-muted"></i>
</div>
```

### آیکون های تعاملی
```html
<!-- دکمه ویرایش -->
<button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
  <i class="fa-solid fa-pen"></i>
</button>

<!-- دکمه حذف -->
<button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200">
  <i class="fa-solid fa-trash"></i>
</button>
```

---

## 📊 جداول

### جدول استاندارد
```html
<div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
  <table class="w-full">
    <thead class="bg-bg-secondary border-b border-border-light">
      <tr>
        <th class="px-xl py-md text-right text-sm font-semibold text-text-secondary leading-normal">نام</th>
        <th class="px-xl py-md text-right text-sm font-semibold text-text-secondary leading-normal">وضعیت</th>
        <th class="px-xl py-md text-right text-sm font-semibold text-text-secondary leading-normal">عملیات</th>
      </tr>
    </thead>
    <tbody>
      <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
        <td class="px-xl py-md text-base text-text-primary leading-normal">محمدرضا</td>
        <td class="px-xl py-md">
          <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-800 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
            <i class="fa-solid fa-circle text-[6px]"></i>
            فعال
          </span>
        </td>
        <td class="px-xl py-md">
          <div class="flex items-center gap-2">
            <button class="..."><i class="fa-solid fa-pen"></i></button>
            <button class="..."><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

---

## 🎭 Modal

### Modal پایه
```html
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
  <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[700px] w-full max-h-[90vh] overflow-y-auto">
    <!-- Header -->
    <div class="px-3xl py-xl border-b border-border-light flex items-center justify-between">
      <h3 class="text-xl font-semibold text-text-primary leading-snug">عنوان Modal</h3>
      <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
        <i class="fa-solid fa-times"></i>
      </button>
    </div>
    
    <!-- Body -->
    <div class="p-3xl text-base leading-relaxed">
      <!-- محتوا -->
    </div>
    
    <!-- Footer -->
    <div class="px-3xl py-xl border-t border-border-light flex items-center justify-end gap-2">
      <button class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium text-base leading-normal">
        انصراف
      </button>
      <button class="bg-primary text-white px-xl py-md rounded-lg font-medium text-base leading-normal">
        تایید
      </button>
    </div>
  </div>
</div>
```

---

## 📱 Responsive Design

### نمایش/مخفی کردن بر اساس اندازه صفحه
```html
<!-- نمایش فقط در موبایل -->
<div class="block lg:hidden">محتوا</div>

<!-- نمایش فقط در دسکتاپ -->
<div class="hidden lg:block">محتوا</div>

<!-- Grid responsive -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3xl">
  <!-- کارت ها -->
</div>
```

---

## 🚨 نکات بسیار مهم - الزامی

### ✅ باید انجام شود
1. **همیشه Tailwind CSS** را از CDN لود کنید
2. **فونت Sahel** برای همه متون فارسی (به جز آیکون ها)
3. **Font Awesome** برای آیکون ها و لود از CDN
4. **یکپارچگی کامل** در استفاده از کلاس های Tailwind
5. **Radio button** به جای select برای ≤3 گزینه
6. **Input Group** برای فرم های فشرده
7. **آیکون در سمت راست** دکمه فارسی با `ml-2`
8. **Toggle با JavaScript** برای RTL - از روش استاندارد این سند استفاده کنید

### ❌ نباید انجام شود
1. **هرگز** از CSS کاستوم استفاده نکنید مگر ضروری باشد
2. **هرگز** مقادیر hex به جای کلاس های Tailwind ننویسید
3. **هرگز** از گرادیان یا رنگ های فانتزی استفاده نکنید
4. **هرگز** از `peer-checked:after:translate-x` برای Toggle در RTL استفاده نکنید
4. **هرگز** فونت Sahel را برای آیکون های Font Awesome اعمال نکنید
5. **هرگز** از استایل های inline استفاده نکنید (به جز موارد بسیار خاص)

---

## 💼 مثال کامل: فرم با کارت

```html
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>فرم نمونه</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Sahel Font -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f172a',
            secondary: '#64748b',
            'text-primary': '#0f172a',
            'text-secondary': '#475569',
            'text-muted': '#64748b',
            'bg-label': '#f8fafc',
            'border-light': '#f1f5f9',
            'border-medium': '#e2e8f0',
          }
        }
      }
    }
  </script>
  
  <style>
    body, button, input, textarea, select, p, div, span, h1, h2, h3, h4, h5, h6 {
      font-family: 'Sahel', sans-serif !important;
    }
    .fa, .fas, .far, .fal, .fab {
      font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Pro', 'Font Awesome 6 Brands' !important;
    }
  </style>
</head>
<body class="bg-gray-50">
  
  <div class="max-w-[800px] mx-auto px-8 py-20">
    
    <!-- کارت فرم -->
    <div class="bg-white border border-border-light rounded-3xl shadow-sm overflow-hidden">
      
      <!-- Header -->
      <div class="px-8 py-5 border-b border-border-light">
        <h2 class="text-xl font-semibold text-text-primary tracking-tight leading-snug">اطلاعات کاربری</h2>
      </div>
      
      <!-- Body -->
      <div class="p-8 space-y-5">
        
        <!-- نام کاربری -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(15,23,42,0.05)] transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[140px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              نام کاربری
            </label>
            <input type="text" 
                   class="flex-1 px-4 py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                   placeholder="نام کاربری خود را وارد کنید">
          </div>
        </div>
        
        <!-- ایمیل -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(15,23,42,0.05)] transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[140px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              ایمیل
            </label>
            <input type="email" 
                   class="flex-1 px-4 py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                   placeholder="example@domain.com">
          </div>
        </div>
        
        <!-- وضعیت (Radio) -->
        <div>
          <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت حساب</label>
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="active" class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">فعال</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status" value="inactive" class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">غیرفعال</span>
            </label>
          </div>
        </div>
        
      </div>
      
      <!-- Footer -->
      <div class="px-8 py-5 border-t border-border-light flex items-center justify-end gap-3">
        <button class="bg-gray-100 text-text-secondary border border-border-medium px-5 py-3 rounded-lg font-medium hover:bg-gray-200 transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button class="bg-primary text-white px-5 py-3 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-[0_4px_12px_rgba(15,23,42,0.15)] transition-all duration-200 flex items-center gap-2 text-base leading-normal">
          <i class="fa-solid fa-check ml-2"></i>
          <span>ذخیره تغییرات</span>
        </button>
      </div>
      
    </div>
    
  </div>
  
</body>
</html>
```

اگر داشبورد ادمین داشته باشیم:
داشبورد ادمین باید به صورت کامل ریسپانسیو پیاده سازی شود. در دسکتاپ، داشبورد در سمت راست صفحه نمایش داده می شود. در موبایل، یک آیکون منو در بالای راست صفحه قرار می گیرد که با کلیک روی آن، سایدبار به سبک Slide-in از سمت راست باز می شود.

---

## 🎓 نتیجه گیری

این دستورالعمل بر اساس **Tailwind CSS** طراحی شده تا:
- ✅ **نگهداری آسان** داشته باشید
- ✅ **یکپارچگی کامل** در طراحی
- ✅ **سرعت بالا** در توسعه
- ✅ **طراحی مدرن و حرفه ای**
- ✅ **بدون CSS اضافی** (مگر ضروری)

**توجه:** همیشه از کلاس های Tailwind استفاده کنید و فقط در موارد **بسیار ضروری** CSS کاستوم بنویسید. این رویکرد باعث **یکپارچگی، سرعت و نگهداری آسان** می شود.