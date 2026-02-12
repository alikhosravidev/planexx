<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه محصول
$productId = $_GET['id'] ?? 1;

// تنظیمات صفحه
$pageTitle   = 'ویرایش محصول';
$currentPage = 'products-list';

// داده نمونه محصول (در پروژه واقعی از دیتابیس)
$product = [
    'id'         => 1,
    'title'      => 'لپ‌تاپ ایسوس VivoBook',
    'slug'       => 'asus-vivobook',
    'sku'        => 'PRD-001',
    'category'   => 'electronic',
    'status'     => 'active',
    'price'      => '45000000',
    'sale_price' => '42000000',
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'محصولات', 'url' => '/dashboard/products/products/list.php'],
    ['label' => 'ویرایش: ' . $product['title']],
];

$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/products/products/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
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
      
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        
        <!-- اطلاعات اصلی محصول -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات اصلی محصول</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- عنوان محصول -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  عنوان محصول <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="title" required value="<?= htmlspecialchars($product['title']) ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <!-- نامک (Slug) -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نامک (Slug)
                </label>
                <input type="text" name="slug" dir="ltr" value="<?= htmlspecialchars($product['slug']) ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <!-- کد محصول -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد محصول (SKU) <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="sku" required dir="ltr" value="<?= htmlspecialchars($product['sku']) ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <!-- دسته‌بندی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  دسته‌بندی
                </label>
                <select name="category" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="electronic" <?= $product['category'] === 'electronic' ? 'selected' : '' ?>>الکترونیکی</option>
                  <option value="food" <?= $product['category']       === 'food' ? 'selected' : '' ?>>خوراکی</option>
                  <option value="health" <?= $product['category']     === 'health' ? 'selected' : '' ?>>بهداشتی</option>
                  <option value="industrial" <?= $product['category'] === 'industrial' ? 'selected' : '' ?>>صنعتی</option>
                  <option value="service" <?= $product['category']    === 'service' ? 'selected' : '' ?>>خدماتی</option>
                </select>
              </div>
            </div>
            

            <!-- وضعیت -->
            <div>
              <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت محصول</label>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="active" <?= $product['status'] === 'active' ? 'checked' : '' ?> class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">فعال</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="draft" <?= $product['status'] === 'draft' ? 'checked' : '' ?> class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">پیش‌نویس</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="inactive" <?= $product['status'] === 'inactive' ? 'checked' : '' ?> class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">غیرفعال</span>
                </label>
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- اطلاعات قیمت و انبار -->
        <div class="bg-white border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">قیمت‌گذاری و انبار</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  قیمت (ریال) <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="price" required value="<?= $product['price'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  قیمت با تخفیف
                </label>
                <input type="text" name="sale_price" value="<?= $product['sale_price'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- دکمه‌های عملیاتی -->
        <div class="flex items-center justify-end gap-3">
          <a href="/dashboard/products/products/list.php" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            انصراف
          </a>
          <button type="submit" class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
            <i class="fa-solid fa-check ml-2"></i>
            <span>ذخیره تغییرات</span>
          </button>
        </div>
        
      </form>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
</body>
</html>
