<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تابع تبدیل اعداد انگلیسی به فارسی
function toPersianNumber($number) {
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($english, $persian, (string)$number);
}

// تنظیمات صفحه
$pageTitle = 'مدیریت نقش‌ها';
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی (خالی برای این صفحه)
$actionButtons = [];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => 'مدیریت نقش‌ها و دسترسی‌ها'],
];

// داده‌های نمونه (در پروژه واقعی از دیتابیس)
$roles = [
    ['id' => 1, 'name' => 'مدیر سیستم', 'users_count' => 2, 'permissions_count' => 45, 'guard_name' => 'web', 'created_at' => '1402/08/12'],
    ['id' => 2, 'name' => 'مدیر ارشد', 'users_count' => 5, 'permissions_count' => 35, 'guard_name' => 'web', 'created_at' => '1402/08/15'],
    ['id' => 3, 'name' => 'مدیر فروش', 'users_count' => 8, 'permissions_count' => 18, 'guard_name' => 'web', 'created_at' => '1402/08/20'],
    ['id' => 4, 'name' => 'کارشناس فروش', 'users_count' => 25, 'permissions_count' => 12, 'guard_name' => 'web', 'created_at' => '1402/09/01'],
    ['id' => 5, 'name' => 'کارشناس فنی', 'users_count' => 18, 'permissions_count' => 15, 'guard_name' => 'web', 'created_at' => '1402/09/05'],
    ['id' => 6, 'name' => 'منابع انسانی', 'users_count' => 4, 'permissions_count' => 20, 'guard_name' => 'web', 'created_at' => '1402/09/10'],
    ['id' => 7, 'name' => 'مدیر مالی', 'users_count' => 3, 'permissions_count' => 22, 'guard_name' => 'web', 'created_at' => '1402/09/12'],
    ['id' => 8, 'name' => 'کارشناس مالی', 'users_count' => 6, 'permissions_count' => 14, 'guard_name' => 'web', 'created_at' => '1402/09/14'],
    ['id' => 9, 'name' => 'مدیر مارکتینگ', 'users_count' => 4, 'permissions_count' => 16, 'guard_name' => 'web', 'created_at' => '1402/09/18'],
    ['id' => 10, 'name' => 'کارشناس مارکتینگ', 'users_count' => 12, 'permissions_count' => 10, 'guard_name' => 'web', 'created_at' => '1402/09/20'],
    ['id' => 11, 'name' => 'کارشناس CRM', 'users_count' => 8, 'permissions_count' => 12, 'guard_name' => 'web', 'created_at' => '1402/09/22'],
    ['id' => 12, 'name' => 'مدیر آموزش', 'users_count' => 2, 'permissions_count' => 15, 'guard_name' => 'web', 'created_at' => '1402/09/25'],
    ['id' => 13, 'name' => 'مشتری', 'users_count' => 48, 'permissions_count' => 5, 'guard_name' => 'web', 'created_at' => '1402/09/28'],
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
      
      <!-- جدول نقش‌ها -->
      <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">نام نقش</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">تعداد کاربران</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">تعداد دسترسی‌ها</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">تاریخ ایجاد</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($roles as $role): ?>
                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-primary"></i>
                      </div>
                      <span class="text-base text-text-primary font-medium leading-normal"><?= $role['name'] ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-users"></i>
                      <?= toPersianNumber($role['users_count']) ?> نفر
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-key"></i>
                      <?= toPersianNumber($role['permissions_count']) ?> دسترسی
                    </span>
                  </td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= toPersianNumber($role['created_at']) ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <a href="/dashboard/org/roles-permissions/permissions.php?role_id=<?= $role['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="مدیریت دسترسی‌ها">
                        <i class="fa-solid fa-key"></i>
                      </a>
                      <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <!-- دکمه افزودن نقش جدید -->
        <div class="px-6 py-4 border-t border-border-light flex justify-start">
          <button class="bg-primary text-white px-xl py-md rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-base leading-normal" onclick="openRoleModal()">
            <i class="fa-solid fa-plus"></i>
            <span>افزودن نقش جدید</span>
          </button>
        </div>
      </div>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
</body>
</html>

