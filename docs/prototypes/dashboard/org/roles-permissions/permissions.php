<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت ID نقش از URL
$roleId = isset($_GET['role_id']) ? intval($_GET['role_id']) : 1;

// تابع تبدیل اعداد به فارسی
function toPersianNumber($number) {
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($english, $persian, (string)$number);
}

// داده‌های نقش‌ها (در پروژه واقعی از دیتابیس)
$roles = [
    1 => ['id' => 1, 'name' => 'مدیر سیستم', 'users_count' => 2],
    2 => ['id' => 2, 'name' => 'مدیر ارشد', 'users_count' => 5],
    3 => ['id' => 3, 'name' => 'مدیر فروش', 'users_count' => 8],
    4 => ['id' => 4, 'name' => 'کارشناس فروش', 'users_count' => 25],
    5 => ['id' => 5, 'name' => 'کارشناس فنی', 'users_count' => 18],
    6 => ['id' => 6, 'name' => 'منابع انسانی', 'users_count' => 4],
    7 => ['id' => 7, 'name' => 'مدیر مالی', 'users_count' => 3],
    8 => ['id' => 8, 'name' => 'کارشناس مالی', 'users_count' => 6],
    9 => ['id' => 9, 'name' => 'مدیر مارکتینگ', 'users_count' => 4],
    10 => ['id' => 10, 'name' => 'کارشناس مارکتینگ', 'users_count' => 12],
    11 => ['id' => 11, 'name' => 'کارشناس CRM', 'users_count' => 8],
    12 => ['id' => 12, 'name' => 'مدیر آموزش', 'users_count' => 2],
    13 => ['id' => 13, 'name' => 'مشتری', 'users_count' => 48],
];

$currentRole = $roles[$roleId] ?? $roles[1];

// تنظیمات صفحه
$pageTitle = 'مدیریت دسترسی‌های نقش: ' . $currentRole['name'];
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی
$actionButtons = [];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => 'مدیریت نقش‌ها', 'url' => '/dashboard/org/roles-permissions/roles.php'],
    ['label' => 'دسترسی‌های نقش: ' . $currentRole['name']],
];

// لیست پرمیشن‌های استاندارد
$standardPermissions = [
    'list' => ['id' => 'list', 'name' => 'مشاهده لیست', 'icon' => 'fa-solid fa-list'],
    'show' => ['id' => 'show', 'name' => 'مشاهده جزئیات', 'icon' => 'fa-solid fa-eye'],
    'store' => ['id' => 'store', 'name' => 'ایجاد', 'icon' => 'fa-solid fa-plus'],
    'update' => ['id' => 'update', 'name' => 'ویرایش', 'icon' => 'fa-solid fa-pen'],
    'delete' => ['id' => 'delete', 'name' => 'حذف', 'icon' => 'fa-solid fa-trash'],
    'import' => ['id' => 'import', 'name' => 'ایمپورت داده', 'icon' => 'fa-solid fa-file-import'],
    'export' => ['id' => 'export', 'name' => 'خروجی گرفتن', 'icon' => 'fa-solid fa-file-export'],
];

// ماژول‌ها و موجودیت‌ها با پرمیشن‌ها
$modules = [
    [
        'id' => 'org',
        'name' => 'ساختار سازمانی',
        'icon' => 'fa-solid fa-sitemap',
        'color' => 'blue',
        'entities' => [
            ['id' => 'users', 'name' => 'کاربران', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'departments', 'name' => 'دپارتمان‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
            ['id' => 'roles', 'name' => 'نقش‌ها', 'permissions' => ['list' => true, 'show' => false, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
            ['id' => 'job_positions', 'name' => 'موقعیت‌های شغلی', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
        ]
    ],
    [
        'id' => 'documents',
        'name' => 'مدیریت اسناد و فایل‌ها',
        'icon' => 'fa-solid fa-folder-open',
        'color' => 'amber',
        'entities' => [
            ['id' => 'files', 'name' => 'فایل‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => false, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'folders', 'name' => 'پوشه‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
        ]
    ],
    [
        'id' => 'tasks',
        'name' => 'مدیریت وظایف',
        'icon' => 'fa-solid fa-list-check',
        'color' => 'amber',
        'entities' => [
            ['id' => 'tasks', 'name' => 'وظایف', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => true, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'task_categories', 'name' => 'دسته‌بندی وظایف', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
        ]
    ],
    [
        'id' => 'knowledge',
        'name' => 'پایگاه تجربه سازمانی',
        'icon' => 'fa-solid fa-book',
        'color' => 'teal',
        'entities' => [
            ['id' => 'experiences', 'name' => 'تجربه‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => true, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'templates', 'name' => 'قالب‌های تجربه', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
        ]
    ],
    [
        'id' => 'finance',
        'name' => 'مالی و وصول مطالبات',
        'icon' => 'fa-solid fa-coins',
        'color' => 'green',
        'entities' => [
            ['id' => 'invoices', 'name' => 'فاکتورها', 'permissions' => ['list' => false, 'show' => false, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
            ['id' => 'payments', 'name' => 'پرداخت‌ها', 'permissions' => ['list' => false, 'show' => false, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
            ['id' => 'receivables', 'name' => 'مطالبات', 'permissions' => ['list' => false, 'show' => false, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
        ]
    ],
    [
        'id' => 'crm',
        'name' => 'CRM',
        'icon' => 'fa-solid fa-users-line',
        'color' => 'purple',
        'entities' => [
            ['id' => 'customers', 'name' => 'مشتریان', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => true, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'leads', 'name' => 'سرنخ‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => true, 'delete' => false, 'import' => false, 'export' => true]],
            ['id' => 'opportunities', 'name' => 'فرصت‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
            ['id' => 'contacts', 'name' => 'مخاطبین', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => true, 'delete' => false, 'import' => false, 'export' => true]],
        ]
    ],
];

// تعریف رنگ‌ها برای ماژول‌ها
$moduleColors = [
    'blue' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-200',
        'hover' => 'hover:border-blue-300',
        'icon' => 'text-blue-600',
        'iconBg' => 'bg-blue-100',
        'gradient' => 'from-blue-300/60',
        'header' => 'bg-blue-50/80',
        'checkbox' => 'accent-blue-600',
        'headerText' => 'text-blue-700',
    ],
    'amber' => [
        'bg' => 'bg-amber-50',
        'border' => 'border-amber-200',
        'hover' => 'hover:border-amber-300',
        'icon' => 'text-amber-600',
        'iconBg' => 'bg-amber-100',
        'gradient' => 'from-amber-300/60',
        'header' => 'bg-amber-50/80',
        'checkbox' => 'accent-amber-600',
        'headerText' => 'text-amber-700',
    ],
    'stone' => [
        'bg' => 'bg-stone-50',
        'border' => 'border-stone-200',
        'hover' => 'hover:border-stone-300',
        'icon' => 'text-stone-600',
        'iconBg' => 'bg-stone-200',
        'gradient' => 'from-stone-400/50',
        'header' => 'bg-stone-100/80',
        'checkbox' => 'accent-stone-600',
        'headerText' => 'text-stone-700',
    ],
    'teal' => [
        'bg' => 'bg-teal-50',
        'border' => 'border-teal-200',
        'hover' => 'hover:border-teal-300',
        'icon' => 'text-teal-600',
        'iconBg' => 'bg-teal-100',
        'gradient' => 'from-teal-300/60',
        'header' => 'bg-teal-50/80',
        'checkbox' => 'accent-teal-600',
        'headerText' => 'text-teal-700',
    ],
    'green' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-200',
        'hover' => 'hover:border-green-300',
        'icon' => 'text-green-600',
        'iconBg' => 'bg-green-100',
        'gradient' => 'from-green-300/60',
        'header' => 'bg-green-50/80',
        'checkbox' => 'accent-green-600',
        'headerText' => 'text-green-700',
    ],
    'purple' => [
        'bg' => 'bg-purple-50',
        'border' => 'border-purple-200',
        'hover' => 'hover:border-purple-300',
        'icon' => 'text-purple-600',
        'iconBg' => 'bg-purple-100',
        'gradient' => 'from-purple-300/60',
        'header' => 'bg-purple-50/80',
        'checkbox' => 'accent-purple-600',
        'headerText' => 'text-purple-700',
    ],
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
      
      <!-- هدر نقش -->
      <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-shield-halved text-2xl text-primary"></i>
            </div>
            <div>
              <h2 class="text-xl font-bold text-text-primary leading-snug"><?= $currentRole['name'] ?></h2>
              <p class="text-sm text-text-secondary leading-normal mt-1">
                <i class="fa-solid fa-users ml-1"></i>
                <?= toPersianNumber($currentRole['users_count']) ?> کاربر با این نقش
              </p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <button onclick="selectAllPermissions()" class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
              <i class="fa-solid fa-check-double"></i>
              <span>انتخاب همه</span>
            </button>
            <button onclick="deselectAllPermissions()" class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
              <i class="fa-solid fa-xmark"></i>
              <span>حذف انتخاب‌ها</span>
            </button>
          </div>
        </div>
      </div>
      
      <!-- ماژول‌ها و دسترسی‌ها -->
      <div class="space-y-6">
        <?php foreach ($modules as $module): ?>
          <?php $colors = $moduleColors[$module['color']] ?? $moduleColors['blue']; ?>
          
          <div class="bg-bg-primary border <?= $colors['border'] ?> rounded-2xl overflow-hidden <?= $colors['hover'] ?> transition-all duration-200">
            <!-- هدر ماژول -->
            <div class="<?= $colors['header'] ?> border-b <?= $colors['border'] ?> px-6 py-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 <?= $colors['iconBg'] ?> rounded-lg flex items-center justify-center">
                    <i class="<?= $module['icon'] ?> text-lg <?= $colors['icon'] ?>"></i>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-text-primary leading-snug"><?= $module['name'] ?></h3>
                    <p class="text-xs text-text-secondary leading-normal"><?= toPersianNumber(count($module['entities'])) ?> موجودیت</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <button onclick="toggleModulePermissions('<?= $module['id'] ?>', true)" class="text-xs text-text-secondary hover:text-primary transition-colors duration-200">انتخاب همه</button>
                  <span class="text-border-medium">|</span>
                  <button onclick="toggleModulePermissions('<?= $module['id'] ?>', false)" class="text-xs text-text-secondary hover:text-red-600 transition-colors duration-200">حذف انتخاب</button>
                  <button onclick="toggleModule('<?= $module['id'] ?>')" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-white/50 rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-chevron-down module-toggle-icon transition-transform duration-200" id="icon-<?= $module['id'] ?>"></i>
                  </button>
                </div>
              </div>
            </div>
            
            <!-- بدنه ماژول - جدول موجودیت‌ها و پرمیشن‌ها -->
            <div class="module-content" id="content-<?= $module['id'] ?>">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="<?= $colors['bg'] ?> border-b <?= $colors['border'] ?>">
                      <th class="px-6 py-3 text-right text-sm font-semibold text-text-primary leading-normal min-w-[180px]">
                        <div class="flex items-center gap-2">
                          <i class="fa-solid fa-cube text-text-muted"></i>
                          موجودیت
                        </div>
                      </th>
                      <?php foreach ($standardPermissions as $perm): ?>
                        <th class="px-3 py-3 text-center text-xs font-medium <?= $colors['headerText'] ?> leading-normal min-w-[90px]">
                          <div class="flex flex-col items-center gap-1">
                            <i class="<?= $perm['icon'] ?> text-sm opacity-70"></i>
                            <span><?= $perm['name'] ?></span>
                          </div>
                        </th>
                      <?php endforeach; ?>
                      <th class="px-3 py-3 text-center text-xs font-medium text-text-secondary leading-normal min-w-[70px]">
                        <span>عملیات</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($module['entities'] as $index => $entity): ?>
                      <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary/50 transition-colors duration-200">
                        <td class="px-6 py-4">
                          <span class="text-sm font-medium text-text-primary leading-normal"><?= $entity['name'] ?></span>
                        </td>
                        <?php foreach ($standardPermissions as $permKey => $perm): ?>
                          <td class="px-3 py-4 text-center">
                            <label class="inline-flex items-center justify-center cursor-pointer">
                              <input type="checkbox" 
                                     name="permissions[<?= $module['id'] ?>][<?= $entity['id'] ?>][<?= $permKey ?>]" 
                                     value="1"
                                     data-module="<?= $module['id'] ?>"
                                     data-entity="<?= $module['id'] ?>_<?= $entity['id'] ?>"
                                     data-permission="<?= $permKey ?>"
                                     class="w-4 h-4 <?= $colors['checkbox'] ?> rounded border-border-medium cursor-pointer transition-all duration-200"
                                     <?= ($entity['permissions'][$permKey] ?? false) ? 'checked' : '' ?>>
                            </label>
                          </td>
                        <?php endforeach; ?>
                        <td class="px-3 py-4 text-center">
                          <div class="flex items-center justify-center gap-1">
                            <button onclick="toggleEntityPermissions('<?= $module['id'] ?>_<?= $entity['id'] ?>', true)" 
                                    class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-green-600 hover:bg-green-50 rounded transition-all duration-200" 
                                    title="انتخاب همه">
                              <i class="fa-solid fa-check-double text-xs"></i>
                            </button>
                            <button onclick="toggleEntityPermissions('<?= $module['id'] ?>_<?= $entity['id'] ?>', false)" 
                                    class="w-7 h-7 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" 
                                    title="حذف انتخاب">
                              <i class="fa-solid fa-xmark text-xs"></i>
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
        <?php endforeach; ?>
      </div>
      
      <!-- دکمه‌های پایین صفحه -->
      <div class="mt-8 bg-bg-primary border border-border-light rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-sm text-text-secondary leading-normal">
            <i class="fa-solid fa-info-circle ml-1 text-primary"></i>
            تغییرات دسترسی‌ها بر روی کاربران دارای این نقش اعمال می‌شود.
          </p>
          <div class="flex items-center gap-3">
            <a href="/dashboard/org/roles-permissions/roles.php" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
              انصراف
            </a>
            <button type="submit" class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal">
              <i class="fa-solid fa-check"></i>
              <span>ذخیره تغییرات</span>
            </button>
          </div>
        </div>
      </div>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    // Toggle module content
    function toggleModule(moduleId) {
      const content = document.getElementById('content-' + moduleId);
      const icon = document.getElementById('icon-' + moduleId);
      
      if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.style.transform = 'rotate(0deg)';
      } else {
        content.style.display = 'none';
        icon.style.transform = 'rotate(180deg)';
      }
    }
    
    // Toggle all permissions in a module
    function toggleModulePermissions(moduleId, checked) {
      const checkboxes = document.querySelectorAll(`input[data-module="${moduleId}"]`);
      checkboxes.forEach(cb => cb.checked = checked);
    }
    
    // Toggle all permissions in an entity
    function toggleEntityPermissions(entityId, checked) {
      const checkboxes = document.querySelectorAll(`input[data-entity="${entityId}"]`);
      checkboxes.forEach(cb => cb.checked = checked);
    }
    
    // Select all permissions
    function selectAllPermissions() {
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(cb => cb.checked = true);
    }
    
    // Deselect all permissions
    function deselectAllPermissions() {
      const checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(cb => cb.checked = false);
    }
  </script>
  
</body>
</html>
