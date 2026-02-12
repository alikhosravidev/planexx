<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'مدیریت محصولات';
$currentPage = 'products-list';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'محصولات'],
];

$actionButtons = [
    ['label' => 'افزودن محصول جدید', 'url' => '/dashboard/products/products/create.php', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// دسته‌بندی‌های نمونه
$categories = ['الکترونیکی', 'خوراکی', 'بهداشتی', 'صنعتی', 'خدماتی'];

// داده‌های نمونه محصولات
$products = [
    ['id' => 1, 'title' => 'لپ‌تاپ ایسوس VivoBook', 'sku' => 'PRD-۰۰۱', 'category' => 'الکترونیکی', 'price' => '۴۵,۰۰۰,۰۰۰', 'status' => 'active', 'created_at' => '۱۲ بهمن ۱۴۰۴'],
    ['id' => 2, 'title' => 'ماوس لاجیتک MX Master', 'sku' => 'PRD-۰۰۲', 'category' => 'الکترونیکی', 'price' => '۳,۲۰۰,۰۰۰', 'status' => 'active', 'created_at' => '۱۰ بهمن ۱۴۰۴'],
    ['id' => 3, 'title' => 'کیبورد مکانیکی Keychron K8', 'sku' => 'PRD-۰۰۳', 'category' => 'الکترونیکی', 'price' => '۵,۸۰۰,۰۰۰', 'status' => 'active', 'created_at' => '۸ بهمن ۱۴۰۴'],
    ['id' => 4, 'title' => 'روغن زیتون فرابکر', 'sku' => 'PRD-۰۰۴', 'category' => 'خوراکی', 'price' => '۴۸۰,۰۰۰', 'status' => 'active', 'created_at' => '۵ بهمن ۱۴۰۴'],
    ['id' => 5, 'title' => 'شامپو ضدریزش مو', 'sku' => 'PRD-۰۰۵', 'category' => 'بهداشتی', 'price' => '۲۸۰,۰۰۰', 'status' => 'out_of_stock', 'created_at' => '۳ بهمن ۱۴۰۴'],
    ['id' => 6, 'title' => 'پیچ و مهره صنعتی M10', 'sku' => 'PRD-۰۰۶', 'category' => 'صنعتی', 'price' => '۱۵,۰۰۰', 'status' => 'active', 'created_at' => '۱ بهمن ۱۴۰۴'],
    ['id' => 7, 'title' => 'مانیتور سامسونگ ۲۷ اینچ', 'sku' => 'PRD-۰۰۷', 'category' => 'الکترونیکی', 'price' => '۱۲,۵۰۰,۰۰۰', 'status' => 'active', 'created_at' => '۲۸ دی ۱۴۰۴'],
    ['id' => 8, 'title' => 'خدمات نصب و راه‌اندازی', 'sku' => 'PRD-۰۰۸', 'category' => 'خدماتی', 'price' => '۲,۰۰۰,۰۰۰', 'status' => 'draft', 'created_at' => '۲۵ دی ۱۴۰۴'],
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
      
      <!-- فیلترها و جستجو -->
      <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
        <div class="flex flex-wrap items-stretch gap-4">
          
          <!-- جستجو -->
          <div class="flex-1 min-w-[250px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                جستجو
              </label>
              <input type="text" 
                     class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                     placeholder="نام محصول، SKU یا دسته‌بندی">
            </div>
          </div>
          
          <!-- دسته‌بندی -->
          <div class="flex-1 min-w-[200px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                دسته‌بندی
              </label>
              <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option>همه دسته‌بندی‌ها</option>
                <?php foreach ($categories as $cat): ?>
                <option><?= $cat ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
          <!-- وضعیت -->
          <div class="flex-1 min-w-[180px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                وضعیت
              </label>
              <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option>همه</option>
                <option>فعال</option>
                <option>ناموجود</option>
                <option>پیش‌نویس</option>
              </select>
            </div>
          </div>
          
          <!-- دکمه‌ها -->
          <div class="flex items-center gap-2">
            <button class="bg-primary text-white px-xl py-3.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal whitespace-nowrap">
              <i class="fa-solid fa-search ml-2"></i>
              <span>اعمال فیلتر</span>
            </button>
            <button class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal whitespace-nowrap">
              <i class="fa-solid fa-rotate-right ml-2"></i>
              <span>پاک کردن</span>
            </button>
          </div>
          
        </div>
      </div>
      
      <!-- جدول محصولات -->
      <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">محصول</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">کد محصول</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">دسته‌بندی</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">قیمت (ریال)</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">تاریخ ایجاد</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-box text-primary text-base"></i>
                      </div>
                      <span class="text-base text-text-primary font-medium leading-normal"><?= $product['title'] ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal font-mono"><?= $product['sku'] ?></td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <?= $product['category'] ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-base text-text-primary font-medium leading-normal"><?= $product['price'] ?></td>
                  <td class="px-6 py-4">
                    <?php
                    $statusLabels = [
                        'active'       => ['label' => 'فعال', 'class' => 'bg-green-50 text-green-700'],
                        'out_of_stock' => ['label' => 'ناموجود', 'class' => 'bg-red-50 text-red-700'],
                        'draft'        => ['label' => 'پیش‌نویس', 'class' => 'bg-yellow-50 text-yellow-700'],
                    ];
                  $status = $statusLabels[$product['status']] ?? $statusLabels['draft'];
                  ?>
                    <span class="inline-flex items-center gap-1.5 <?= $status['class'] ?> px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-circle text-[6px]"></i>
                      <?= $status['label'] ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-text-secondary leading-normal"><?= $product['created_at'] ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <a href="/dashboard/products/products/view.php?id=<?= $product['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="مشاهده">
                        <i class="fa-solid fa-eye"></i>
                      </a>
                      <a href="/dashboard/products/products/edit.php?id=<?= $product['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                        <i class="fa-solid fa-pen"></i>
                      </a>
                      <button onclick="deleteProduct(<?= $product['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-border-light flex items-center justify-between">
          <div class="text-sm text-text-secondary leading-normal">
            نمایش <span class="font-semibold text-text-primary">۱</span> تا <span class="font-semibold text-text-primary">۸</span> از <span class="font-semibold text-text-primary">۱,۲۴۸</span> محصول
          </div>
          <div class="flex items-center gap-2">
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium">۱</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۲</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۳</button>
            <span class="px-2 text-text-muted">...</span>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۱۵۶</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
          </div>
        </div>
      </div>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- مودال تأیید حذف -->
  <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
      <div class="p-6 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-trash text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-text-primary leading-snug mb-2">حذف محصول</h3>
        <p class="text-base text-text-secondary leading-relaxed mb-6">آیا از حذف این محصول اطمینان دارید؟ این عملیات قابل بازگشت نیست.</p>
        <div class="flex items-center justify-center gap-3">
          <button onclick="closeDeleteModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-base leading-normal">
            انصراف
          </button>
          <button onclick="confirmDelete()" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-trash ml-2"></i>
            حذف محصول
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    let deleteProductId = null;
    
    function deleteProduct(id) {
      deleteProductId = id;
      document.getElementById('deleteModal').classList.remove('hidden');
      document.getElementById('deleteModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteModal() {
      document.getElementById('deleteModal').classList.add('hidden');
      document.getElementById('deleteModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      deleteProductId = null;
    }
    
    function confirmDelete() {
      console.log('Deleting product:', deleteProductId);
      alert('محصول با موفقیت حذف شد');
      closeDeleteModal();
    }
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteModal();
    });
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
        closeDeleteModal();
      }
    });
  </script>
  
</body>
</html>
