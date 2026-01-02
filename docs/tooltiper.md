# Tooltiper Service

سرویس **Tooltiper** یک ابزار سبک و بدون وابستگی برای نمایش tooltip‌ها در پروژه است که به صورت خودکار تمام المان‌های HTML با attribute‌های `title`، `data-tooltip` و `aria-label` را شناسایی و tooltip مناسب را نمایش می‌دهد.

## ویژگی‌ها

- ✅ **بدون وابستگی به jQuery**: کاملاً با Vanilla JavaScript نوشته شده
- ✅ **خودکار**: نیازی به تنظیمات اضافی نیست
- ✅ **امن**: از `textContent` به جای `innerHTML` استفاده می‌کند
- ✅ **پشتیبانی کامل**: از `title`، `data-tooltip` و `aria-label` پشتیبانی می‌کند
- ✅ **بدون tooltip پیش‌فرض مرورگر**: به صورت خودکار tooltip پیش‌فرض مرورگر را غیرفعال می‌کند
- ✅ **موبایل-دوستانه**: تشخیص خودکار دستگاه‌های لمسی
- ✅ **دسترس‌پذیر**: پشتیبانی کامل از accessibility با `role="tooltip"`
- ✅ **عملکرد بهینه**: استفاده از `requestAnimationFrame` برای به‌روزرسانی
- ✅ **RTL**: پشتیبانی کامل از راست‌چین

## نحوه استفاده

### 1. با استفاده از `title` attribute (استاندارد HTML)

```html
<button title="این یک tooltip است">نمایش Tooltip</button>
<i class="fa-solid fa-info" title="اطلاعات بیشتر"></i>
<a href="#" title="برای مشاهده کلیک کنید">لینک</a>
<input type="text" title="نام کاربری را وارد کنید" placeholder="نام کاربری">
```

### 2. با استفاده از `data-tooltip` attribute (پیشنهادی)

```html
<button data-tooltip="این tooltip با data-tooltip است">دکمه</button>
<span data-tooltip="توضیحات تکمیلی">متن</span>
```

### 3. با استفاده از `aria-label` attribute (برای دسترس‌پذیری)

```html
<button aria-label="ویرایش کاربر">
  <i class="fa-solid fa-edit"></i>
</button>
<button aria-label="حذف آیتم">
  <i class="fa-solid fa-trash"></i>
</button>
```

## اولویت attribute‌ها

اگر یک المان چند attribute داشته باشد، اولویت به ترتیب زیر است:

1. `data-tooltip` (بالاترین اولویت)
2. `aria-label`
3. `title` (پایین‌ترین اولویت)

## ویژگی‌های تکنیکی

### جلوگیری از tooltip پیش‌فرض مرورگر

سرویس به صورت خودکار tooltip پیش‌فرض مرورگر را غیرفعال می‌کند:

1. **هنگام نمایش tooltip**: attribute `title` به `data-original-title` منتقل و از المان حذف می‌شود
2. **هنگام مخفی شدن tooltip**: attribute `title` از `data-original-title` بازگردانده می‌شود
3. **در صورت scroll یا resize**: تمام tooltip‌ها پاک شده و `title` بازگردانده می‌شود

این رویکرد تضمین می‌کند که:
- ✅ هیچ tooltip پیش‌فرض مرورگر نمایش داده نمی‌شود
- ✅ attribute `title` برای SEO و accessibility حفظ می‌شود
- ✅ عملکرد بهینه و بدون side effect است

### Event Listeners

سرویس به صورت خودکار به event های زیر گوش می‌دهد:

- `mouseenter` / `mouseleave` - برای نمایش/مخفی کردن tooltip در desktop
- `focus` / `blur` - برای پشتیبانی از keyboard navigation
- `click` - برای toggle کردن tooltip
- `scroll` / `resize` - برای مخفی کردن tooltip‌ها

### تشخیص دستگاه

سرویس به صورت خودکار نوع دستگاه را تشخیص می‌دهد:
- در دستگاه‌های لمسی (موبایل/تبلت): tooltip در حالت focus نمایش داده نمی‌شود
- در desktop: tooltip در همه حالات نمایش داده می‌شود

### موقعیت‌یابی هوشمند

tooltip به صورت خودکار موقعیت خود را تنظیم می‌کند:
- بالای المان نمایش داده می‌شود
- در صورت خروج از صفحه، موقعیت افقی تنظیم می‌شود
- حداقل 4 پیکسل از لبه‌های صفحه فاصله می‌گیرد

## استایل‌دهی سفارشی

می‌توانید استایل tooltip را در فایل CSS خود سفارشی‌سازی کنید:

```css
tool-tip {
  /* استایل‌های دلخواه */
  background-color: rgba(0, 0, 0, 0.9);
  color: #fff;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

tool-tip.visible {
  /* انیمیشن نمایش */
  animation: tooltipFadeIn 0.2s ease-in-out;
}
```

## محدودیت‌ها

- حداکثر عرض tooltip: 300 پیکسل
- همیشه در بالای المان نمایش داده می‌شود (فعلاً پوزیشن پایین پشتیبانی نمی‌شود)
- از HTML در متن tooltip پشتیبانی نمی‌شود (به دلیل امنیت)

## نمونه‌های پیشرفته

### Tooltip پویا

```html
<button id="dynamic-btn" title="کلیک اول">کلیک کنید</button>

<script>
  let count = 0;
  document.getElementById('dynamic-btn').addEventListener('click', function() {
    count++;
    this.setAttribute('title', `کلیک ${count}`);
  });
</script>
```

### Tooltip با محتوای طولانی

```html
<button title="این یک tooltip با متن بلندتر است که به صورت خودکار شکسته می‌شود">
  راهنما
</button>
```

## عیب‌یابی

### Tooltip نمایش داده نمی‌شود

1. مطمئن شوید که المان یکی از attribute‌های `title`، `data-tooltip` یا `aria-label` را دارد
2. بررسی کنید که استایل tooltip در CSS لود شده باشد
3. کنسول مرورگر را برای خطاها بررسی کنید

### Tooltip در موبایل کار نمی‌کند

- در دستگاه‌های لمسی، tooltip فقط با کلیک نمایش داده می‌شود
- از focus event در موبایل پشتیبانی نمی‌شود (رفتار عادی)

### Tooltip در جای اشتباه نمایش داده می‌شود

- مطمئن شوید که المان والد position: relative ندارد که با position: absolute tooltip تداخل کند
- z-index tooltip را افزایش دهید

## توسعه و مشارکت

برای بهبود یا گزارش مشکلات، لطفاً به [issues](../../../issues) مراجعه کنید.

## License

این سرویس تحت لایسنس پروژه Planexx است.
