```php
public function __construct(
    private FavoriteService $favorites,
) {}

// ═══════════════════════════════════════════════════════════════
// افزودن به علاقه‌مندی‌ها
// ═══════════════════════════════════════════════════════════════

$this->favorites->for($product)->by($userId)->add();

// یا با شروع از کاربر
$this->favorites->user($userId)->for($product)->add();


// ═══════════════════════════════════════════════════════════════
// حذف از علاقه‌مندی‌ها
// ═══════════════════════════════════════════════════════════════

$this->favorites->for($product)->by($userId)->remove();


// ═══════════════════════════════════════════════════════════════
// Toggle (اگر هست حذف، اگر نیست اضافه)
// ═══════════════════════════════════════════════════════════════

$isNowFavorited = $this->favorites
    ->for($product)
    ->by($userId)
    ->toggle();

if ($isNowFavorited) {
    echo "Added to favorites";
} else {
    echo "Removed from favorites";
}


// ═══════════════════════════════════════════════════════════════
// بررسی وجود
// ═══════════════════════════════════════════════════════════════

$isFavorited = $this->favorites
    ->for($product)
    ->by($userId)
    ->exists();

// یا
$isFavorited = $this->favorites
    ->for($product)
    ->by($userId)
    ->isFavorited();


// ═══════════════════════════════════════════════════════════════
// دریافت اطلاعات
// ═══════════════════════════════════════════════════════════════

// همه علاقه‌مندی‌های یک محصول
$allFavorites = $this->favorites->for($product)->get();

// تعداد
$count = $this->favorites->for($product)->count();

// آیا علاقه‌مندی دارد؟
$hasFavorites = $this->favorites->for($product)->hasFavorites();

// لیست user_id های علاقه‌مند
$userIds = $this->favorites->for($product)->userIds();


// ═══════════════════════════════════════════════════════════════
// پاکسازی
// ═══════════════════════════════════════════════════════════════

// حذف همه علاقه‌مندی‌های یک محصول
$deletedCount = $this->favorites->for($product)->clear();
```
