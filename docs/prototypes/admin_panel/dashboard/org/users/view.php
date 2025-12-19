<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه کاربر (در پروژه واقعی از دیتابیس)
$userId = $_GET['id'] ?? 1;

// داده‌های نمونه کاربر
$user = [
    'id'                 => 1,
    'full_name'          => 'علی احمدی',
    'first_name'         => 'علی',
    'last_name'          => 'احمدی',
    'mobile'             => '09121234567',
    'email'              => 'ali@example.com',
    'national_code'      => '1234567890',
    'user_type'          => 'employee',
    'user_type_label'    => 'کارمند',
    'customer_type'      => null,
    'gender'             => 'مرد',
    'birth_date'         => '1364/02/25',
    'is_active'          => true,
    'employee_code'      => 'EMP001',
    'job_position'       => 'مدیر فروش',
    'direct_manager'     => 'محمد رضایی',
    'department'         => 'فروش',
    'employment_date'    => '1399/12/10',
    'image_url'          => null,
    'created_at'         => '1402/08/12',
    'last_login_at'      => '2 ساعت پیش',
    'mobile_verified_at' => '1402/08/12',
    'email_verified_at'  => '1402/08/15',
];

// تنظیم عنوان و breadcrumb بر اساس نوع کاربر
$userType   = $user['user_type'];
$typeLabels = [
    'employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'viewTitle' => 'جزئیات کارمند'],
    'customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'viewTitle' => 'جزئیات مشتری'],
    'user'     => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'viewTitle' => 'جزئیات کاربر'],
];

$pageTitle   = isset($typeLabels[$userType]) ? $typeLabels[$userType]['viewTitle'] : 'جزئیات کاربر';
$listTitle   = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
$listUrl     = '/dashboard/org/users/list.php' . ($userType ? '?type=' . $userType : '');
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'ویرایش', 'url' => '/dashboard/org/users/edit.php?id=' . $userId, 'icon' => 'fa-solid fa-pen', 'type' => 'primary'],
    ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => $listTitle, 'url' => $listUrl],
    ['label' => $pageTitle],
];

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
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ستون اصلی -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- اطلاعات شخصی -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات شخصی</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">نام کامل</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['full_name'] ?></p>
              </div>
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">شماره موبایل</p>
                <p class="text-base text-text-primary font-medium leading-normal" dir="ltr"><?= $user['mobile'] ?></p>
              </div>
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">ایمیل</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['email'] ?? '-' ?></p>
              </div>
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">کد ملی</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['national_code'] ?></p>
              </div>
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">جنسیت</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['gender'] ?></p>
              </div>
              
              <div class="border-r-4 border-primary/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">تاریخ تولد</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['birth_date'] ?></p>
              </div>
              
            </div>
          </div>
          
          <?php if ($userType === 'employee'): ?>
          <!-- اطلاعات استخدامی -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات استخدامی</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              
              <div class="border-r-4 border-green-500/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">کد پرسنلی</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['employee_code'] ?></p>
              </div>
              
              <div class="border-r-4 border-green-500/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">موقعیت شغلی</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['job_position'] ?></p>
              </div>
              
              <div class="border-r-4 border-green-500/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">دپارتمان</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['department'] ?></p>
              </div>
              
              <div class="border-r-4 border-green-500/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">مدیر مستقیم</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['direct_manager'] ?></p>
              </div>
              
              <div class="border-r-4 border-green-500/20 pr-4">
                <p class="text-sm text-text-secondary mb-1 leading-normal">تاریخ استخدام</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['employment_date'] ?></p>
              </div>
              
            </div>
          </div>
          <?php endif; ?>
          
        </div>
        
        <!-- ستون کناری -->
        <div class="space-y-6">
          
          <!-- اطلاعات سیستمی -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات سیستمی</h2>
            <div class="space-y-4">
              
              <div>
                <p class="text-sm text-text-secondary mb-1 leading-normal">تاریخ ثبت‌نام</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['created_at'] ?></p>
              </div>
              
              <div>
                <p class="text-sm text-text-secondary mb-1 leading-normal">آخرین ورود</p>
                <p class="text-base text-text-primary font-medium leading-normal"><?= $user['last_login_at'] ?></p>
              </div>
              
              <div>
                <p class="text-sm text-text-secondary mb-2 leading-normal">وضعیت تایید</p>
                <div class="space-y-2">
                  <?php if ($user['mobile_verified_at']): ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-circle-check text-green-600"></i>
                      <span class="text-sm text-text-primary leading-normal">موبایل تایید شده</span>
                    </div>
                  <?php else: ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-circle-xmark text-red-600"></i>
                      <span class="text-sm text-text-primary leading-normal">موبایل تایید نشده</span>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($user['email_verified_at']): ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-circle-check text-green-600"></i>
                      <span class="text-sm text-text-primary leading-normal">ایمیل تایید شده</span>
                    </div>
                  <?php else: ?>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-circle-xmark text-red-600"></i>
                      <span class="text-sm text-text-primary leading-normal">ایمیل تایید نشده</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
            </div>
          </div>
          
          <!-- عملیات سریع -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">عملیات سریع</h2>
            <div class="space-y-3">
              <button class="w-full bg-bg-secondary hover:bg-gray-100 text-text-primary px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-key"></i>
                <span>بازنشانی رمز عبور</span>
              </button>
              <button class="w-full bg-bg-secondary hover:bg-gray-100 text-text-primary px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-envelope"></i>
                <span>ارسال ایمیل</span>
              </button>
              <button class="w-full bg-bg-secondary hover:bg-gray-100 text-text-primary px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-ban"></i>
                <span>مسدود کردن کاربر</span>
              </button>
              <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-3 rounded-lg font-medium transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-trash"></i>
                <span>حذف کاربر</span>
              </button>
            </div>
          </div>
          
        </div>
        
      </div>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

