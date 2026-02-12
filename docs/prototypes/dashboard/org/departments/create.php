<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'افزودن دپارتمان جدید';
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/org/departments/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline']
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => 'مدیریت دپارتمان‌ها', 'url' => '/dashboard/org/departments/list.php'],
    ['label' => 'افزودن دپارتمان جدید'],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('org-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST" enctype="multipart/form-data">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- فرم اصلی -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- اطلاعات پایه -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه</h2>
              
              <div class="space-y-4">
                
                <!-- نام دپارتمان -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      نام دپارتمان <span class="text-red-500 mr-1">*</span>
                    </label>
                    <input type="text" name="name" required
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="نام دپارتمان را وارد کنید">
                  </div>
                </div>
                
                <!-- کد دپارتمان -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      کد دپارتمان
                    </label>
                    <input type="text" name="code"
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="مثال: SALES">
                  </div>
                </div>
                
                <!-- دپارتمان والد -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      دپارتمان والد
                    </label>
                    <select name="parent_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">بدون والد (دپارتمان اصلی)</option>
                      <option value="1">مدیریت</option>
                      <option value="3">فروش</option>
                      <option value="6">فنی</option>
                      <option value="9">منابع انسانی</option>
                    </select>
                  </div>
                </div>
                
                <!-- مدیر دپارتمان -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      مدیر دپارتمان
                    </label>
                    <select name="manager_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">انتخاب کنید</option>
                      <option value="1">محمد رضایی</option>
                      <option value="2">علی احمدی</option>
                      <option value="3">فاطمه محمدی</option>
                    </select>
                  </div>
                </div>
                
                <!-- توضیحات -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
                      توضیحات
                    </label>
                    <textarea rows="4" name="description"
                              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                              placeholder="توضیحات دپارتمان را وارد کنید"></textarea>
                  </div>
                </div>
                
                <!-- انتخاب رنگ -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-start leading-normal pt-4">
                      رنگ دپارتمان
                    </label>
                    <div class="flex-1 px-lg py-3.5 flex flex-wrap gap-2">
                      <?php
                      $colors = [
                          // آبی‌ها
                          'blue-500', 'blue-600', 'blue-700', 'sky-500', 'sky-600', 'cyan-500', 'cyan-600',
                          // سبزها و فیروزه‌ای
                          'teal-500', 'teal-600', 'green-500', 'green-600', 'emerald-500', 'emerald-600', 'lime-500',
                          // زردها و نارنجی‌ها
                          'yellow-500', 'amber-500', 'amber-600', 'orange-500', 'orange-600',
                          // قرمزها و صورتی‌ها
                          'red-500', 'red-600', 'rose-500', 'rose-600', 'pink-500', 'pink-600', 'fuchsia-500',
                          // بنفش‌ها
                          'purple-500', 'purple-600', 'violet-500', 'violet-600', 'indigo-500', 'indigo-600',
                          // خنثی‌ها
                          'slate-500', 'slate-600', 'gray-500', 'gray-600', 'zinc-500', 'zinc-600', 'stone-500'
                      ];
                      foreach ($colors as $index => $color):
                          $checked = ($color === 'blue-500') ? 'checked' : '';
                      ?>
                      <label class="cursor-pointer color-option">
                        <input type="radio" name="color" value="<?= $color ?>" class="peer hidden" <?= $checked ?>>
                        <div class="w-8 h-8 bg-<?= $color ?> rounded-lg border-2 border-transparent peer-checked:border-gray-800 peer-checked:ring-2 peer-checked:ring-gray-300 transition-all flex items-center justify-center">
                          <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 check-icon"></i>
                        </div>
                      </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
                
                <!-- انتخاب آیکون -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-start leading-normal pt-4">
                      آیکون دپارتمان
                    </label>
                    <div class="flex-1 px-lg py-3.5">
                      <div class="flex flex-wrap gap-2">
                        <?php
                        $icons = [
                            // مدیریت و اداری
                            'fa-building', 'fa-building-columns', 'fa-city', 'fa-landmark', 'fa-industry',
                            // فروش و بازاریابی
                            'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-bullhorn', 'fa-rectangle-ad',
                            'fa-store', 'fa-shop', 'fa-cart-shopping', 'fa-basket-shopping', 'fa-tags',
                            // مالی
                            'fa-coins', 'fa-money-bill-wave', 'fa-credit-card', 'fa-wallet', 'fa-piggy-bank',
                            'fa-calculator', 'fa-receipt', 'fa-file-invoice-dollar', 'fa-hand-holding-dollar', 'fa-sack-dollar',
                            // فنی و IT
                            'fa-code', 'fa-laptop-code', 'fa-server', 'fa-database', 'fa-network-wired',
                            'fa-microchip', 'fa-desktop', 'fa-cloud', 'fa-shield-halved', 'fa-bug',
                            // منابع انسانی
                            'fa-users', 'fa-user-tie', 'fa-user-group', 'fa-people-group', 'fa-handshake',
                            'fa-id-card', 'fa-address-card', 'fa-clipboard-user', 'fa-user-plus', 'fa-users-gear',
                            // تولید و عملیات
                            'fa-gears', 'fa-wrench', 'fa-screwdriver-wrench', 'fa-hammer', 'fa-toolbox',
                            'fa-cogs', 'fa-robot', 'fa-warehouse', 'fa-boxes-stacked', 'fa-dolly',
                            // آموزش و دانش
                            'fa-graduation-cap', 'fa-book', 'fa-book-open', 'fa-chalkboard-user', 'fa-school',
                            'fa-user-graduate', 'fa-award', 'fa-certificate', 'fa-medal', 'fa-trophy',
                            // ارتباطات و پشتیبانی
                            'fa-headset', 'fa-phone', 'fa-comments', 'fa-envelope', 'fa-paper-plane',
                            'fa-life-ring', 'fa-circle-question', 'fa-message', 'fa-bell', 'fa-at',
                            // قراردادها و حقوقی
                            'fa-file-contract', 'fa-file-signature', 'fa-scale-balanced', 'fa-gavel', 'fa-stamp',
                            'fa-pen-to-square', 'fa-file-pen', 'fa-clipboard-check', 'fa-folder-open', 'fa-briefcase',
                            // پژوهش و توسعه
                            'fa-flask', 'fa-microscope', 'fa-atom', 'fa-dna', 'fa-lightbulb',
                            'fa-magnifying-glass', 'fa-compass', 'fa-rocket', 'fa-satellite', 'fa-brain',
                            // حمل‌ونقل و لجستیک
                            'fa-truck', 'fa-plane', 'fa-ship', 'fa-train', 'fa-car',
                            // سلامت و بهداشت
                            'fa-hospital', 'fa-house-medical', 'fa-heart-pulse', 'fa-stethoscope', 'fa-kit-medical',
                            // محیط زیست و انرژی
                            'fa-leaf', 'fa-tree', 'fa-sun', 'fa-bolt', 'fa-wind',
                            // سایر
                            'fa-star', 'fa-gem', 'fa-crown', 'fa-flag', 'fa-globe',
                            'fa-map-location-dot', 'fa-location-dot', 'fa-calendar-days'
                        ];
                        foreach ($icons as $index => $icon):
                            $checked = ($icon === 'fa-building') ? 'checked' : '';
                        ?>
                        <label class="cursor-pointer icon-option">
                          <input type="radio" name="icon" value="<?= $icon ?>" class="peer hidden" <?= $checked ?>>
                          <div class="w-10 h-10 bg-bg-secondary rounded-lg border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary/10 transition-all flex items-center justify-center hover:bg-gray-100">
                            <i class="fa-solid <?= $icon ?> text-lg text-text-secondary peer-checked:text-primary"></i>
                          </div>
                        </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- وضعیت -->
                <div class="flex items-center gap-6 px-lg py-3.5">
                  <label class="text-sm text-text-secondary leading-normal min-w-[140px]">
                    وضعیت
                  </label>
                  <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-primary accent-primary">
                      <span class="text-base text-text-primary leading-normal">فعال</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="0" class="w-4 h-4 text-primary accent-primary">
                      <span class="text-base text-text-primary leading-normal">غیرفعال</span>
                    </label>
                  </div>
                </div>
                
              </div>
            </div>
            
          </div>
          
          <!-- راهنما و پیش‌نمایش -->
          <div class="space-y-6">
            
            <!-- راهنما -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                  <i class="fa-solid fa-lightbulb text-white"></i>
                </div>
                <h3 class="text-base font-semibold text-blue-800 leading-normal">راهنما</h3>
              </div>
              <ul class="space-y-3">
                <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>نام دپارتمان باید واضح و مشخص باشد</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>کد دپارتمان برای دسته‌بندی استفاده می‌شود</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>دپارتمان والد برای ایجاد ساختار درختی استفاده می‌شود</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-blue-700 leading-relaxed">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>مدیر دپارتمان مسئول نظارت بر کارمندان است</span>
                </li>
              </ul>
            </div>
            
            <!-- آمار -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <h3 class="text-base font-semibold text-text-primary leading-normal mb-4">آمار کلی</h3>
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-text-secondary leading-normal">کل دپارتمان‌ها</span>
                  <span class="text-sm font-semibold text-text-primary leading-normal">۱۰</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-text-secondary leading-normal">دپارتمان‌های فعال</span>
                  <span class="text-sm font-semibold text-text-primary leading-normal">۹</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-text-secondary leading-normal">سطوح سازمانی</span>
                  <span class="text-sm font-semibold text-text-primary leading-normal">۳</span>
                </div>
              </div>
            </div>
            
          </div>
          
        </div>
        
        <!-- دکمه‌های عملیات -->
        <div class="flex items-center gap-3 mt-6">
          <button type="submit" class="bg-green-600 text-white px-xl py-md rounded-lg font-medium hover:bg-green-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-check ml-2"></i>
            <span>ذخیره دپارتمان</span>
          </button>
          <a href="/dashboard/org/departments/list.php" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-times ml-2"></i>
            <span>انصراف</span>
          </a>
        </div>
        
      </form>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    // نمایش تیک برای رنگ انتخاب شده
    document.querySelectorAll('.color-option input').forEach(input => {
      input.addEventListener('change', function() {
        document.querySelectorAll('.color-option .check-icon').forEach(icon => {
          icon.classList.add('opacity-0');
          icon.classList.remove('opacity-100');
        });
        if (this.checked) {
          this.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
          this.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
        }
      });
    });
    
    // نمایش انتخاب آیکون
    document.querySelectorAll('.icon-option input').forEach(input => {
      input.addEventListener('change', function() {
        document.querySelectorAll('.icon-option > div').forEach(div => {
          div.classList.remove('border-primary', 'bg-primary/10');
          div.classList.add('border-transparent');
          div.querySelector('i').classList.remove('text-primary');
          div.querySelector('i').classList.add('text-text-secondary');
        });
        if (this.checked) {
          this.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
          this.nextElementSibling.classList.remove('border-transparent');
          this.nextElementSibling.querySelector('i').classList.add('text-primary');
          this.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
        }
      });
    });
    
    // نمایش تیک اولیه برای رنگ پیش‌فرض
    document.addEventListener('DOMContentLoaded', function() {
      const checkedColor = document.querySelector('.color-option input:checked');
      if (checkedColor) {
        checkedColor.nextElementSibling.querySelector('.check-icon').classList.remove('opacity-0');
        checkedColor.nextElementSibling.querySelector('.check-icon').classList.add('opacity-100');
      }
      
      const checkedIcon = document.querySelector('.icon-option input:checked');
      if (checkedIcon) {
        checkedIcon.nextElementSibling.classList.add('border-primary', 'bg-primary/10');
        checkedIcon.nextElementSibling.classList.remove('border-transparent');
        checkedIcon.nextElementSibling.querySelector('i').classList.add('text-primary');
        checkedIcon.nextElementSibling.querySelector('i').classList.remove('text-text-secondary');
      }
    });
  </script>
</body>
</html>

