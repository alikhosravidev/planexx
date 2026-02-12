<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'افزودن محصول جدید';
$currentPage = 'products-list';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'محصولات', 'url' => '/dashboard/products/products/list.php'],
    ['label' => 'افزودن محصول جدید'],
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
                <input type="text" name="title" required
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="عنوان محصول را وارد کنید">
              </div>
            </div>
            
            <!-- نامک (Slug) -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نامک (Slug)
                </label>
                <input type="text" name="slug" dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="product-slug">
              </div>
            </div>
            
            <!-- کد محصول (SKU) -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد محصول (SKU) <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="sku" required dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="PRD-001">
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
                  <option value="electronic">الکترونیکی</option>
                  <option value="food">خوراکی</option>
                  <option value="health">بهداشتی</option>
                  <option value="industrial">صنعتی</option>
                  <option value="service">خدماتی</option>
                </select>
              </div>
            </div>
            
            <!-- وضعیت -->
            <div>
              <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت محصول</label>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="active" checked class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">فعال</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="draft" class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">پیش‌نویس</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="status" value="inactive" class="w-4 h-4 text-primary accent-primary">
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
            
            <!-- قیمت -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  قیمت (ریال) <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="price" required
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="۰">
              </div>
            </div>
            
            <!-- قیمت با تخفیف -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  قیمت با تخفیف
                </label>
                <input type="text" name="sale_price"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="خالی بگذارید اگر تخفیف ندارد">
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- تصویر محصول -->
        <div class="bg-white border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">تصویر محصول</h2>
          
          <div class="border-2 border-dashed border-border-medium rounded-xl p-8 text-center hover:border-primary transition-all duration-200 cursor-pointer" onclick="document.getElementById('productImage').click()">
            <input type="file" id="productImage" name="image" accept="image/*" class="hidden">
            <div class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-solid fa-cloud-upload-alt text-2xl text-text-muted"></i>
            </div>
            <p class="text-base text-text-primary font-medium leading-normal mb-1">تصویر محصول را انتخاب کنید</p>
            <p class="text-sm text-text-muted leading-normal">فرمت‌های مجاز: JPG, PNG, WebP - حداکثر ۲ مگابایت</p>
          </div>
        </div>
        
        <!-- دکمه‌های عملیاتی -->
        <div class="flex items-center justify-end gap-3">
          <a href="/dashboard/products/products/list.php" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            انصراف
          </a>
          <button type="submit" name="action" value="draft" class="bg-bg-secondary text-text-primary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-save ml-2"></i>
            ذخیره پیش‌نویس
          </button>
          <button type="submit" name="action" value="publish" class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
            <i class="fa-solid fa-check ml-2"></i>
            <span>ذخیره و انتشار</span>
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
