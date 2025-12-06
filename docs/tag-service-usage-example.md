```php
public function __construct(
    private TagService $tags,
) {}

// ═══════════════════════════════════════════════════════════════
// 📦 Entity-Centric Operations (شروع از entity)
// ═══════════════════════════════════════════════════════════════

// Attach یک تگ
$this->tags->for($product)->attach($tag);
$this->tags->for($product)->attach(5); // با ID

// Attach چند تگ
$this->tags->for($product)->attach([$tag1, $tag2]);
$this->tags->for($product)->attach([1, 2, 3]);

// Detach
$this->tags->for($product)->detach($tag);
$this->tags->for($product)->detach([1, 2, 3]);

// Sync (جایگزینی همه تگ‌ها)
$this->tags->for($product)->sync([1, 2, 3]);
$this->tags->for($product)->replaceWith([1, 2, 3]); // alias

// حذف همه تگ‌ها
$this->tags->for($product)->clear();

// بررسی‌ها
$this->tags->for($product)->has($tag);        // آیا این تگ را دارد؟
$this->tags->for($product)->hasAny([1, 2]);   // آیا هیچکدام را دارد؟
$this->tags->for($product)->hasAll([1, 2]);   // آیا همه را دارد؟
$this->tags->for($product)->isTagged();       // آیا تگ دارد؟
$this->tags->for($product)->isEmpty();        // آیا تگ ندارد؟

// دریافت اطلاعات
$tags   = $this->tags->for($product)->get();      // همه تگ‌ها
$tagIds = $this->tags->for($product)->tagIds();   // فقط ID ها
$count  = $this->tags->for($product)->count();    // تعداد
$first  = $this->tags->for($product)->first();    // اولین تگ


// ═══════════════════════════════════════════════════════════════
// 🏷️ Tag-Centric Operations (شروع از tag)
// ═══════════════════════════════════════════════════════════════

// Attach/Detach
$this->tags->tag($tag)->attachTo($product);
$this->tags->tag($tag)->attachToMany([$product1, $product2]);
$this->tags->tag($tag)->detachFrom($product);
$this->tags->tag($tag)->detachFromMany([$product1, $product2]);

// بررسی اتصال
$isAttached = $this->tags->tag($tag)->isAttachedTo($product);

// دریافت entity ها
$products = $this->tags->tag($tag)->entities(Product::class);
$count    = $this->tags->tag($tag)->entitiesCount(Product::class);
$exists   = $this->tags->tag($tag)->hasEntities(Product::class);

// مدیریت usage_count
$this->tags->tag($tag)->incrementUsage();
$this->tags->tag($tag)->decrementUsage();
$this->tags->tag($tag)->resetUsage();
$count = $this->tags->tag($tag)->usageCount();

// بررسی استفاده
$isUsed   = $this->tags->tag($tag)->isUsed();
$isUnused = $this->tags->tag($tag)->isUnused();


// ═══════════════════════════════════════════════════════════════
// 📝 CRUD Operations
// ═══════════════════════════════════════════════════════════════

$tag = $this->tags->create($dto);
$tag = $this->tags->update($tag, $dto);
$this->tags->delete($tag);


// ═══════════════════════════════════════════════════════════════
// 🔗 Method Chaining Examples
// ═══════════════════════════════════════════════════════════════

// ساختن محصول با تگ‌ها
$this->tags
    ->for($product)
    ->attach([1, 2, 3])
    ->attach($newTag);

// تگ کردن چند entity یکجا
$this->tags
    ->tag($saleTag)
    ->attachTo($product1)
    ->attachTo($product2)
    ->attachTo($product3);

// یا
$this->tags
    ->tag($saleTag)
    ->attachToMany([$product1, $product2, $product3]);

// جایگزینی تگ‌ها
$this->tags
    ->for($product)
    ->clear()
    ->attach($newTags);
```
