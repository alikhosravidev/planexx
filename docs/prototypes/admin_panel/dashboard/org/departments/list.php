<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تابع تبدیل اعداد به فارسی
function toPersianNumber($number)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    return str_replace($english, $persian, (string)$number);
}

// تنظیمات صفحه
$pageTitle   = 'مدیریت دپارتمان‌ها';
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'افزودن دپارتمان جدید', 'url' => '/dashboard/org/departments/create.php', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => 'مدیریت دپارتمان‌ها'],
];

// داده‌های نمونه دپارتمان‌ها با ساختار درختی (در پروژه واقعی از دیتابیس)
$departments = [
    [
        'id'              => 1,
        'name'            => 'مدیریت',
        'code'            => 'MGT',
        'manager'         => 'محمد رضایی',
        'employees_count' => 8,
        'is_active'       => true,
        'color'           => 'purple',
        'children'        => [
            [
                'id'              => 2,
                'name'            => 'مدیریت اجرایی',
                'code'            => 'EXEC',
                'manager'         => 'علی احمدی',
                'employees_count' => 5,
                'is_active'       => true,
                'color'           => 'purple',
                'children'        => [],
            ],
        ],
    ],
    [
        'id'              => 3,
        'name'            => 'فروش',
        'code'            => 'SALES',
        'manager'         => 'فاطمه محمدی',
        'employees_count' => 42,
        'is_active'       => true,
        'color'           => 'orange',
        'children'        => [
            [
                'id'              => 4,
                'name'            => 'فروش تهران',
                'code'            => 'SALES-THR',
                'manager'         => 'حسین کریمی',
                'employees_count' => 25,
                'is_active'       => true,
                'color'           => 'orange',
                'children'        => [],
            ],
            [
                'id'              => 5,
                'name'            => 'فروش شهرستان',
                'code'            => 'SALES-CTY',
                'manager'         => 'زهرا حسینی',
                'employees_count' => 17,
                'is_active'       => true,
                'color'           => 'orange',
                'children'        => [],
            ],
        ],
    ],
    [
        'id'              => 6,
        'name'            => 'فنی',
        'code'            => 'TECH',
        'manager'         => 'رضا موسوی',
        'employees_count' => 35,
        'is_active'       => true,
        'color'           => 'blue',
        'children'        => [
            [
                'id'              => 7,
                'name'            => 'توسعه نرم‌افزار',
                'code'            => 'DEV',
                'manager'         => 'سارا قاسمی',
                'employees_count' => 18,
                'is_active'       => true,
                'color'           => 'blue',
                'children'        => [],
            ],
            [
                'id'              => 8,
                'name'            => 'پشتیبانی',
                'code'            => 'SUP',
                'manager'         => 'امیر عباسی',
                'employees_count' => 12,
                'is_active'       => true,
                'color'           => 'blue',
                'children'        => [],
            ],
        ],
    ],
    [
        'id'              => 9,
        'name'            => 'منابع انسانی',
        'code'            => 'HR',
        'manager'         => 'مریم نوری',
        'employees_count' => 8,
        'is_active'       => true,
        'color'           => 'pink',
        'children'        => [],
    ],
    [
        'id'              => 10,
        'name'            => 'مالی',
        'code'            => 'FIN',
        'manager'         => 'احمد باقری',
        'employees_count' => 12,
        'is_active'       => false,
        'color'           => 'green',
        'children'        => [],
    ],
];

// تابع نمایش دپارتمان به صورت بازگشتی
function renderDepartment($dept, $level = 0)
{
    $indent      = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    $hasChildren = !empty($dept['children']);
    ?>
    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200 department-row" data-level="<?= $level ?>" data-dept-id="<?= $dept['id'] ?>" data-has-children="<?= $hasChildren ? '1' : '0' ?>">
      <td class="px-6 py-4">
        <div class="flex items-center gap-3">
          <?php if ($hasChildren): ?>
            <button class="toggle-dept w-6 h-6 flex items-center justify-center text-text-muted hover:text-primary transition-colors" data-parent-id="<?= $dept['id'] ?>">
              <i class="fa-solid fa-chevron-down transition-transform duration-200"></i>
            </button>
          <?php else: ?>
            <div class="w-6"></div>
          <?php endif; ?>
          <?= $indent ?>
          <i class="fa-solid fa-building text-primary"></i>
          <span class="text-base text-text-primary font-medium leading-normal"><?= $dept['name'] ?></span>
        </div>
      </td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $dept['code'] ?></td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $dept['manager'] ?></td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= toPersianNumber($dept['employees_count']) ?> نفر</td>
      <td class="px-6 py-4">
        <?php if ($dept['is_active']): ?>
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
          <a href="edit.php?id=<?= $dept['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
            <i class="fa-solid fa-pen"></i>
          </a>
          <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>
    <?php
    if ($hasChildren) {
        foreach ($dept['children'] as $child) {
            renderDepartmentWithParent($child, $level + 1, $dept['id']);
        }
    }
}

// تابع نمایش دپارتمان با شناسه والد
function renderDepartmentWithParent($dept, $level, $parentId)
{
    $indent      = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    $hasChildren = !empty($dept['children']);
    ?>
    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200 department-row child-of-<?= $parentId ?>" data-level="<?= $level ?>" data-dept-id="<?= $dept['id'] ?>" data-parent-id="<?= $parentId ?>" data-has-children="<?= $hasChildren ? '1' : '0' ?>">
      <td class="px-6 py-4">
        <div class="flex items-center gap-3">
          <?php if ($hasChildren): ?>
            <button class="toggle-dept w-6 h-6 flex items-center justify-center text-text-muted hover:text-primary transition-colors" data-parent-id="<?= $dept['id'] ?>">
              <i class="fa-solid fa-chevron-down transition-transform duration-200"></i>
            </button>
          <?php else: ?>
            <div class="w-6"></div>
          <?php endif; ?>
          <?= $indent ?>
          <i class="fa-solid fa-building text-primary"></i>
          <span class="text-base text-text-primary font-medium leading-normal"><?= $dept['name'] ?></span>
        </div>
      </td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $dept['code'] ?></td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $dept['manager'] ?></td>
      <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= toPersianNumber($dept['employees_count']) ?> نفر</td>
      <td class="px-6 py-4">
        <?php if ($dept['is_active']): ?>
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
          <a href="edit.php?id=<?= $dept['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
            <i class="fa-solid fa-pen"></i>
          </a>
          <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>
    <?php
    if ($hasChildren) {
        foreach ($dept['children'] as $child) {
            renderDepartmentWithParent($child, $level + 1, $dept['id']);
        }
    }
}

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('org-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <!-- آمار کلی -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-blue-200 transition-all duration-200 relative overflow-hidden group">
          <!-- Large Transparent Icon Background -->
          <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
            <i class="fa-solid fa-building text-[108px] text-blue-500"></i>
          </div>
          
          <!-- Content -->
          <div class="relative z-10">
            <div class="text-sm text-text-secondary mb-2 font-medium leading-normal">کل دپارتمان‌ها</div>
            <div class="text-4xl font-bold text-text-primary leading-tight">۱۰</div>
          </div>
        </div>
        
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-green-200 transition-all duration-200 relative overflow-hidden group">
          <!-- Large Transparent Icon Background -->
          <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
            <i class="fa-solid fa-check-circle text-[108px] text-green-500"></i>
          </div>
          
          <!-- Content -->
          <div class="relative z-10">
            <div class="text-sm text-text-secondary mb-2 font-medium leading-normal">دپارتمان‌های فعال</div>
            <div class="text-4xl font-bold text-text-primary leading-tight">۹</div>
          </div>
        </div>
        
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-purple-200 transition-all duration-200 relative overflow-hidden group">
          <!-- Large Transparent Icon Background -->
          <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
            <i class="fa-solid fa-users text-[108px] text-purple-500"></i>
          </div>
          
          <!-- Content -->
          <div class="relative z-10">
            <div class="text-sm text-text-secondary mb-2 font-medium leading-normal">کل کارمندان</div>
            <div class="text-4xl font-bold text-text-primary leading-tight">۱۲۲</div>
          </div>
        </div>
        
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-orange-200 transition-all duration-200 relative overflow-hidden group">
          <!-- Large Transparent Icon Background -->
          <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
            <i class="fa-solid fa-sitemap text-[108px] text-orange-500"></i>
          </div>
          
          <!-- Content -->
          <div class="relative z-10">
            <div class="text-sm text-text-secondary mb-2 font-medium leading-normal">سطوح سازمانی</div>
            <div class="text-4xl font-bold text-text-primary leading-tight">۳</div>
          </div>
        </div>
      </div>
      
      <!-- نمایش سلسله مراتبی -->
      <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
          <h2 class="text-lg font-semibold text-text-primary leading-snug">ساختار سازمانی</h2>
          <div class="flex items-center gap-2">
            <a href="chart.php" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm leading-normal flex items-center">
              <i class="fa-solid fa-sitemap ml-2"></i>
              <span>ساختار گرافیکی</span>
            </a>
            <button class="expand-all bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal">
              <i class="fa-solid fa-expand ml-2"></i>
              <span>باز کردن همه</span>
            </button>
            <button class="collapse-all bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal">
              <i class="fa-solid fa-compress ml-2"></i>
              <span>بستن همه</span>
            </button>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">نام دپارتمان</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">کد</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">مدیر</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">تعداد کارمندان</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($departments as $dept): ?>
                <?php renderDepartment($dept); ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- نمایش گرافیکی سلسله مراتب -->
      <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">نمودار ساختار سازمانی</h2>
        <div class="flex justify-center items-start gap-8 overflow-x-auto pb-4">
          
          <!-- سطح اول -->
          <div class="flex flex-col items-center min-w-[200px]">
            <div class="bg-primary text-white rounded-xl p-4 text-center shadow-md">
              <i class="fa-solid fa-building text-2xl mb-2"></i>
              <p class="text-base font-semibold leading-normal">مدیریت</p>
              <p class="text-sm leading-normal opacity-90">محمد رضایی</p>
              <p class="text-xs leading-normal opacity-75 mt-1">۸ نفر</p>
            </div>
            
            <!-- خط اتصال -->
            <div class="w-0.5 h-8 bg-border-medium"></div>
            
            <!-- سطح دوم -->
            <div class="flex gap-4">
              <div class="flex flex-col items-center">
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-3 text-center">
                  <p class="text-sm font-semibold text-blue-800 leading-normal">مدیریت اجرایی</p>
                  <p class="text-xs text-blue-600 leading-normal mt-1">۵ نفر</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- سطح اول - فروش -->
          <div class="flex flex-col items-center min-w-[200px]">
            <div class="bg-green-500 text-white rounded-xl p-4 text-center shadow-md">
              <i class="fa-solid fa-chart-line text-2xl mb-2"></i>
              <p class="text-base font-semibold leading-normal">فروش</p>
              <p class="text-sm leading-normal opacity-90">فاطمه محمدی</p>
              <p class="text-xs leading-normal opacity-75 mt-1">۴۲ نفر</p>
            </div>
            
            <div class="w-0.5 h-8 bg-border-medium"></div>
            
            <div class="flex gap-4">
              <div class="bg-green-50 border-2 border-green-200 rounded-xl p-3 text-center">
                <p class="text-sm font-semibold text-green-800 leading-normal">فروش تهران</p>
                <p class="text-xs text-green-600 leading-normal mt-1">۲۵ نفر</p>
              </div>
              <div class="bg-green-50 border-2 border-green-200 rounded-xl p-3 text-center">
                <p class="text-sm font-semibold text-green-800 leading-normal">فروش شهرستان</p>
                <p class="text-xs text-green-600 leading-normal mt-1">۱۷ نفر</p>
              </div>
            </div>
          </div>
          
          <!-- سطح اول - فنی -->
          <div class="flex flex-col items-center min-w-[200px]">
            <div class="bg-purple-500 text-white rounded-xl p-4 text-center shadow-md">
              <i class="fa-solid fa-code text-2xl mb-2"></i>
              <p class="text-base font-semibold leading-normal">فنی</p>
              <p class="text-sm leading-normal opacity-90">رضا موسوی</p>
              <p class="text-xs leading-normal opacity-75 mt-1">۳۵ نفر</p>
            </div>
            
            <div class="w-0.5 h-8 bg-border-medium"></div>
            
            <div class="flex gap-4">
              <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-3 text-center">
                <p class="text-sm font-semibold text-purple-800 leading-normal">توسعه نرم‌افزار</p>
                <p class="text-xs text-purple-600 leading-normal mt-1">۱۸ نفر</p>
              </div>
              <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-3 text-center">
                <p class="text-sm font-semibold text-purple-800 leading-normal">پشتیبانی</p>
                <p class="text-xs text-purple-600 leading-normal mt-1">۱۲ نفر</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    // مدیریت باز و بسته شدن دپارتمان‌ها
    document.addEventListener('DOMContentLoaded', function() {
      const toggleButtons = document.querySelectorAll('.toggle-dept');
      
      // تابع مخفی/نمایش فرزندان
      function toggleChildren(parentId, show) {
        const children = document.querySelectorAll(`.child-of-${parentId}`);
        children.forEach(child => {
          if (show) {
            child.style.display = '';
          } else {
            child.style.display = 'none';
            // اگر فرزند هم والد باشد، فرزندانش را هم مخفی کن
            const childId = child.dataset.deptId;
            if (child.dataset.hasChildren === '1') {
              toggleChildren(childId, false);
              const childBtn = child.querySelector('.toggle-dept');
              if (childBtn) {
                const childIcon = childBtn.querySelector('i');
                childIcon.style.transform = 'rotate(0deg)';
              }
            }
          }
        });
      }
      
      toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          const parentId = this.dataset.parentId;
          const icon = this.querySelector('i');
          const isOpen = icon.style.transform === 'rotate(-180deg)';
          
          if (isOpen) {
            // بستن
            icon.style.transform = 'rotate(0deg)';
            toggleChildren(parentId, false);
          } else {
            // باز کردن
            icon.style.transform = 'rotate(-180deg)';
            toggleChildren(parentId, true);
          }
        });
      });
      
      // باز کردن همه
      document.querySelector('.expand-all')?.addEventListener('click', function() {
        document.querySelectorAll('.department-row').forEach(row => {
          row.style.display = '';
        });
        toggleButtons.forEach(btn => {
          const icon = btn.querySelector('i');
          icon.style.transform = 'rotate(-180deg)';
        });
      });
      
      // بستن همه
      document.querySelector('.collapse-all')?.addEventListener('click', function() {
        document.querySelectorAll('.department-row[data-parent-id]').forEach(row => {
          row.style.display = 'none';
        });
        toggleButtons.forEach(btn => {
          const icon = btn.querySelector('i');
          icon.style.transform = 'rotate(0deg)';
        });
      });
    });
  </script>
  
</body>
</html>

