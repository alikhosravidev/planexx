<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'دسته‌بندی محصولات';
$currentPage = 'product-categories';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'دسته‌بندی محصولات'],
];

$actionButtons = [
    ['label' => 'افزودن دسته‌بندی', 'onclick' => 'openCreateCategoryModal()', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// آیکون‌های پیشنهادی
$categoryIcons = [
    'fa-solid fa-microchip', 'fa-solid fa-laptop', 'fa-solid fa-desktop', 'fa-solid fa-mobile-screen',
    'fa-solid fa-tv', 'fa-solid fa-headphones', 'fa-solid fa-camera', 'fa-solid fa-print',
    'fa-solid fa-utensils', 'fa-solid fa-mug-hot', 'fa-solid fa-wheat-awn', 'fa-solid fa-apple-whole',
    'fa-solid fa-pills', 'fa-solid fa-pump-soap', 'fa-solid fa-spray-can-sparkles', 'fa-solid fa-heart-pulse',
    'fa-solid fa-industry', 'fa-solid fa-gears', 'fa-solid fa-wrench', 'fa-solid fa-hammer',
    'fa-solid fa-briefcase', 'fa-solid fa-handshake', 'fa-solid fa-headset', 'fa-solid fa-truck',
    'fa-solid fa-box', 'fa-solid fa-boxes-stacked', 'fa-solid fa-tag', 'fa-solid fa-tags',
    'fa-solid fa-car', 'fa-solid fa-shirt', 'fa-solid fa-gem', 'fa-solid fa-couch',
    'fa-solid fa-book', 'fa-solid fa-palette', 'fa-solid fa-futbol', 'fa-solid fa-baby',
    'fa-solid fa-leaf', 'fa-solid fa-bolt', 'fa-solid fa-shield-halved', 'fa-solid fa-flask',
];

// داده‌های نمونه دسته‌بندی‌ها
$categories = [
    [
        'id'             => 1,
        'name'           => 'الکترونیکی',
        'slug'           => 'electronic',
        'parent_id'      => null,
        'parent_name'    => null,
        'icon'           => 'fa-solid fa-microchip',
        'description'    => 'محصولات الکترونیکی و دیجیتال',
        'sort_order'     => 1,
        'is_active'      => true,
        'products_count' => 385,
        'children_count' => 4,
    ],
    [
        'id'             => 2,
        'name'           => 'خوراکی',
        'slug'           => 'food',
        'parent_id'      => null,
        'parent_name'    => null,
        'icon'           => 'fa-solid fa-utensils',
        'description'    => 'مواد غذایی و خوراکی',
        'sort_order'     => 2,
        'is_active'      => true,
        'products_count' => 240,
        'children_count' => 3,
    ],
    [
        'id'             => 3,
        'name'           => 'بهداشتی',
        'slug'           => 'health',
        'parent_id'      => null,
        'parent_name'    => null,
        'icon'           => 'fa-solid fa-heart-pulse',
        'description'    => 'محصولات بهداشتی و سلامت',
        'sort_order'     => 3,
        'is_active'      => true,
        'products_count' => 180,
        'children_count' => 2,
    ],
    [
        'id'             => 4,
        'name'           => 'صنعتی',
        'slug'           => 'industrial',
        'parent_id'      => null,
        'parent_name'    => null,
        'icon'           => 'fa-solid fa-industry',
        'description'    => 'تجهیزات و مواد صنعتی',
        'sort_order'     => 4,
        'is_active'      => true,
        'products_count' => 312,
        'children_count' => 5,
    ],
    [
        'id'             => 5,
        'name'           => 'خدماتی',
        'slug'           => 'service',
        'parent_id'      => null,
        'parent_name'    => null,
        'icon'           => 'fa-solid fa-headset',
        'description'    => 'خدمات و سرویس‌های قابل ارائه',
        'sort_order'     => 5,
        'is_active'      => true,
        'products_count' => 131,
        'children_count' => 0,
    ],
    [
        'id'             => 6,
        'name'           => 'لپ‌تاپ و نوت‌بوک',
        'slug'           => 'laptops',
        'parent_id'      => 1,
        'parent_name'    => 'الکترونیکی',
        'icon'           => 'fa-solid fa-laptop',
        'description'    => 'انواع لپ‌تاپ و نوت‌بوک',
        'sort_order'     => 1,
        'is_active'      => true,
        'products_count' => 95,
        'children_count' => 0,
    ],
    [
        'id'             => 7,
        'name'           => 'موبایل و تبلت',
        'slug'           => 'mobile-tablet',
        'parent_id'      => 1,
        'parent_name'    => 'الکترونیکی',
        'icon'           => 'fa-solid fa-mobile-screen',
        'description'    => 'انواع گوشی موبایل و تبلت',
        'sort_order'     => 2,
        'is_active'      => true,
        'products_count' => 120,
        'children_count' => 0,
    ],
    [
        'id'             => 8,
        'name'           => 'لبنیات',
        'slug'           => 'dairy',
        'parent_id'      => 2,
        'parent_name'    => 'خوراکی',
        'icon'           => 'fa-solid fa-mug-hot',
        'description'    => 'شیر و لبنیات',
        'sort_order'     => 1,
        'is_active'      => true,
        'products_count' => 55,
        'children_count' => 0,
    ],
    [
        'id'             => 9,
        'name'           => 'ابزار دستی',
        'slug'           => 'hand-tools',
        'parent_id'      => 4,
        'parent_name'    => 'صنعتی',
        'icon'           => 'fa-solid fa-wrench',
        'description'    => 'ابزارآلات دستی',
        'sort_order'     => 1,
        'is_active'      => false,
        'products_count' => 42,
        'children_count' => 0,
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('products-sidebar', ['currentPage' => $currentPage]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Module Header -->
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
      
      <!-- آمار دسته‌بندی‌ها -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-bg-primary border border-border-light rounded-2xl p-5 flex items-center gap-4">
          <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-folder-tree text-blue-600 text-lg"></i>
          </div>
          <div>
            <div class="text-sm text-text-muted leading-normal">کل دسته‌بندی‌ها</div>
            <div class="text-2xl font-bold text-text-primary leading-tight"><?= toPersianNum(count($categories)) ?></div>
          </div>
        </div>
        <div class="bg-bg-primary border border-border-light rounded-2xl p-5 flex items-center gap-4">
          <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-check-circle text-green-600 text-lg"></i>
          </div>
          <div>
            <div class="text-sm text-text-muted leading-normal">دسته‌بندی فعال</div>
            <div class="text-2xl font-bold text-text-primary leading-tight"><?= toPersianNum(count(array_filter($categories, fn ($c) => $c['is_active']))) ?></div>
          </div>
        </div>
        <div class="bg-bg-primary border border-border-light rounded-2xl p-5 flex items-center gap-4">
          <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-sitemap text-purple-600 text-lg"></i>
          </div>
          <div>
            <div class="text-sm text-text-muted leading-normal">دسته‌بندی اصلی</div>
            <div class="text-2xl font-bold text-text-primary leading-tight"><?= toPersianNum(count(array_filter($categories, fn ($c) => $c['parent_id'] === null))) ?></div>
          </div>
        </div>
      </div>
      
      <!-- جدول دسته‌بندی‌ها -->
      <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
        <!-- جستجو و فیلتر -->
        <div class="px-6 py-4 border-b border-border-light">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex-1 max-w-md border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light px-3 py-2.5 text-sm text-text-secondary flex items-center leading-normal">
                  <i class="fa-solid fa-search"></i>
                </label>
                <input type="text" id="categorySearch"
                       class="flex-1 px-3 py-2.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="جستجو در دسته‌بندی‌ها..." oninput="filterCategories()">
              </div>
            </div>
            <div class="flex items-center gap-3">
              <select id="filterParent" onchange="filterCategories()" class="border border-border-medium rounded-xl px-4 py-2.5 text-sm text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option value="">همه دسته‌بندی‌ها</option>
                <option value="root">فقط دسته‌بندی‌های اصلی</option>
                <option value="sub">فقط زیردسته‌بندی‌ها</option>
              </select>
              <span class="text-sm text-text-muted leading-normal whitespace-nowrap">
                <?= toPersianNum(count($categories)) ?> دسته‌بندی
              </span>
            </div>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">ردیف</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">دسته‌بندی</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">نامک</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">والد</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">محصولات</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">زیردسته‌ها</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">ترتیب</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $index => $cat): ?>
                <tr class="category-row border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200"
                    data-name="<?= $cat['name'] ?>" data-slug="<?= $cat['slug'] ?>" data-type="<?= $cat['parent_id'] === null ? 'root' : 'sub' ?>">
                  <td class="px-6 py-4 text-sm text-text-muted leading-normal"><?= toPersianNum($index + 1) ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <?php if ($cat['icon']): ?>
                      <div class="w-9 h-9 bg-bg-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="<?= $cat['icon'] ?> text-text-secondary"></i>
                      </div>
                      <?php endif; ?>
                      <div>
                        <span class="text-base text-text-primary font-medium leading-normal"><?= $cat['name'] ?></span>
                        <?php if ($cat['description']): ?>
                          <p class="text-xs text-text-muted leading-normal mt-0.5"><?= $cat['description'] ?></p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-text-secondary font-mono leading-normal" dir="ltr"><?= $cat['slug'] ?></td>
                  <td class="px-6 py-4 text-sm text-text-secondary leading-normal">
                    <?php if ($cat['parent_name']): ?>
                      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                        <i class="fa-solid fa-folder text-[10px]"></i>
                        <?= $cat['parent_name'] ?>
                      </span>
                    <?php else: ?>
                      <span class="text-text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-sm text-text-primary font-medium leading-normal"><?= toPersianNum($cat['products_count']) ?></td>
                  <td class="px-6 py-4 text-sm text-text-primary leading-normal"><?= toPersianNum($cat['children_count']) ?></td>
                  <td class="px-6 py-4 text-sm text-text-muted leading-normal"><?= toPersianNum($cat['sort_order']) ?></td>
                  <td class="px-6 py-4">
                    <?php if ($cat['is_active']): ?>
                      <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        فعال
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        غیرفعال
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <button onclick="openEditCategoryModal(<?= $cat['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                        <i class="fa-solid fa-pen"></i>
                      </button>
                      <button onclick="deleteCategory(<?= $cat['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- مودال ایجاد/ویرایش دسته‌بندی -->
  <div id="categoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl w-full max-w-[650px] shadow-2xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-border-light flex items-center justify-between sticky top-0 bg-white rounded-t-3xl z-10">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-folder-tree text-primary"></i>
          </div>
          <div>
            <h3 id="categoryModalTitle" class="text-lg font-bold text-text-primary leading-snug">افزودن دسته‌بندی</h3>
            <p class="text-sm text-text-muted leading-normal">اطلاعات دسته‌بندی را وارد کنید</p>
          </div>
        </div>
        <button onclick="closeCategoryModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      
      <!-- Body -->
      <div class="p-6 space-y-5">
        
        <!-- نام دسته‌بندی -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              نام دسته‌بندی <span class="text-red-500 mr-1">*</span>
            </label>
            <input type="text" id="catName" required
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                   placeholder="مثال: الکترونیکی">
          </div>
        </div>
        
        <!-- نامک (Slug) -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              نامک (Slug) <span class="text-red-500 mr-1">*</span>
            </label>
            <input type="text" id="catSlug" required dir="ltr"
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal font-mono"
                   placeholder="electronic" pattern="[a-z0-9\-]+">
          </div>
        </div>
        
        <!-- دسته‌بندی والد و ترتیب -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <!-- دسته‌بندی والد -->
          <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                والد
              </label>
              <select id="catParentId" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option value="">بدون والد (دسته‌بندی اصلی)</option>
                <?php foreach ($categories as $cat): ?>
                  <?php if ($cat['parent_id'] === null): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
          <!-- ترتیب نمایش -->
          <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                ترتیب نمایش
              </label>
              <input type="number" id="catSortOrder" min="0" value="0"
                     class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                     placeholder="۰">
            </div>
          </div>
        </div>
        
        <!-- توضیحات -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
              توضیحات
            </label>
            <textarea id="catDescription" rows="2"
                      class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                      placeholder="توضیح مختصر درباره این دسته‌بندی"></textarea>
          </div>
        </div>
        
        <!-- انتخاب آیکون -->
        <div>
          <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
            <i class="fa-solid fa-icons text-slate-400 ml-2"></i>
            آیکون دسته‌بندی
          </label>
          <input type="hidden" id="selectedCatIcon" value="">
          <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary">
            <div class="grid grid-cols-10 sm:grid-cols-13 gap-2" id="catIconGrid">
              <?php foreach ($categoryIcons as $icon): ?>
              <button type="button" onclick="selectCatIcon(this, '<?= $icon ?>')" 
                      class="cat-icon-btn w-10 h-10 flex items-center justify-center rounded-lg border border-transparent hover:border-primary hover:bg-primary/10 transition-all duration-200 text-text-secondary hover:text-primary"
                      data-icon="<?= $icon ?>">
                <i class="<?= $icon ?>"></i>
              </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- وضعیت -->
        <div>
          <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت</label>
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="cat_status" value="active" checked class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">فعال</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="cat_status" value="inactive" class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">غیرفعال</span>
            </label>
          </div>
        </div>
        
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 sticky bottom-0 bg-bg-secondary rounded-b-3xl">
        <button onclick="closeCategoryModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button onclick="saveCategory()" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
          <i class="fa-solid fa-check ml-2"></i>
          ذخیره دسته‌بندی
        </button>
      </div>
    </div>
  </div>
  
  <!-- مودال حذف دسته‌بندی -->
  <div id="deleteCategoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
      <div class="p-6 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-trash text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-text-primary leading-snug mb-2">حذف دسته‌بندی</h3>
        <p class="text-base text-text-secondary leading-relaxed mb-6">آیا از حذف این دسته‌بندی اطمینان دارید؟ محصولات مرتبط بدون دسته‌بندی خواهند شد.</p>
        <div class="flex items-center justify-center gap-3">
          <button onclick="closeDeleteCategoryModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-base leading-normal">
            انصراف
          </button>
          <button onclick="confirmDeleteCategory()" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-trash ml-2"></i>
            حذف دسته‌بندی
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    let editingCategoryId = null;
    let deleteCategoryId = null;
    
    // انتخاب آیکون
    function selectCatIcon(btn, icon) {
      document.querySelectorAll('.cat-icon-btn').forEach(b => {
        b.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
        b.classList.add('border-transparent');
      });
      btn.classList.remove('border-transparent');
      btn.classList.add('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
      document.getElementById('selectedCatIcon').value = icon;
    }
    
    // باز کردن مودال ایجاد
    function openCreateCategoryModal() {
      editingCategoryId = null;
      document.getElementById('categoryModalTitle').textContent = 'افزودن دسته‌بندی';
      document.getElementById('catName').value = '';
      document.getElementById('catSlug').value = '';
      document.getElementById('catParentId').value = '';
      document.getElementById('catSortOrder').value = '0';
      document.getElementById('catDescription').value = '';
      document.getElementById('selectedCatIcon').value = '';
      document.querySelector('input[name="cat_status"][value="active"]').checked = true;
      
      document.querySelectorAll('.cat-icon-btn').forEach(b => {
        b.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
        b.classList.add('border-transparent');
      });
      
      document.getElementById('categoryModal').classList.remove('hidden');
      document.getElementById('categoryModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    // ویرایش دسته‌بندی
    function openEditCategoryModal(id) {
      editingCategoryId = id;
      document.getElementById('categoryModalTitle').textContent = 'ویرایش دسته‌بندی';
      
      // در پروژه واقعی اطلاعات از سرور لود می‌شود
      document.getElementById('categoryModal').classList.remove('hidden');
      document.getElementById('categoryModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    // بستن مودال
    function closeCategoryModal() {
      document.getElementById('categoryModal').classList.add('hidden');
      document.getElementById('categoryModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      editingCategoryId = null;
    }
    
    // ذخیره
    function saveCategory() {
      const name = document.getElementById('catName').value.trim();
      const slug = document.getElementById('catSlug').value.trim();
      
      if (!name || !slug) {
        alert('لطفاً نام و نامک دسته‌بندی را وارد کنید');
        return;
      }
      
      const data = {
        name: name,
        slug: slug,
        parent_id: document.getElementById('catParentId').value || null,
        sort_order: parseInt(document.getElementById('catSortOrder').value) || 0,
        description: document.getElementById('catDescription').value.trim(),
        icon: document.getElementById('selectedCatIcon').value,
        is_active: document.querySelector('input[name="cat_status"]:checked').value === 'active',
      };
      
      console.log(editingCategoryId ? 'Updating category:' : 'Creating category:', data);
      alert(editingCategoryId ? 'دسته‌بندی با موفقیت ویرایش شد' : 'دسته‌بندی با موفقیت ایجاد شد');
      closeCategoryModal();
    }
    
    // حذف
    function deleteCategory(id) {
      deleteCategoryId = id;
      document.getElementById('deleteCategoryModal').classList.remove('hidden');
      document.getElementById('deleteCategoryModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteCategoryModal() {
      document.getElementById('deleteCategoryModal').classList.add('hidden');
      document.getElementById('deleteCategoryModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      deleteCategoryId = null;
    }
    
    function confirmDeleteCategory() {
      console.log('Deleting category:', deleteCategoryId);
      alert('دسته‌بندی با موفقیت حذف شد');
      closeDeleteCategoryModal();
    }
    
    // فیلتر جدول
    function filterCategories() {
      const search = document.getElementById('categorySearch').value.trim().toLowerCase();
      const filterType = document.getElementById('filterParent').value;
      
      document.querySelectorAll('.category-row').forEach(row => {
        const name = row.dataset.name.toLowerCase();
        const slug = row.dataset.slug.toLowerCase();
        const type = row.dataset.type;
        
        let matchSearch = !search || name.includes(search) || slug.includes(search);
        let matchType = !filterType || type === filterType;
        
        row.style.display = (matchSearch && matchType) ? '' : 'none';
      });
    }
    
    // تولید خودکار slug از نام
    document.getElementById('catName')?.addEventListener('input', function() {
      if (!editingCategoryId) {
        // فقط در حالت ایجاد، slug خودکار تولید نشود (کاربر خودش وارد کند)
      }
    });
    
    // بستن مودال‌ها
    document.getElementById('categoryModal').addEventListener('click', function(e) {
      if (e.target === this) closeCategoryModal();
    });
    document.getElementById('deleteCategoryModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteCategoryModal();
    });
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (!document.getElementById('categoryModal').classList.contains('hidden')) closeCategoryModal();
        if (!document.getElementById('deleteCategoryModal').classList.contains('hidden')) closeDeleteCategoryModal();
      }
    });
  </script>
  
</body>
</html>
