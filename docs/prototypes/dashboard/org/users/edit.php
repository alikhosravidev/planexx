<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه کاربر (در پروژه واقعی از دیتابیس)
$userId = $_GET['id'] ?? 1;

// داده‌های نمونه کاربر
$user = [
    'id' => 1,
    'full_name' => 'علی احمدی',
    'first_name' => 'علی',
    'last_name' => 'احمدی',
    'mobile' => '09121234567',
    'email' => 'ali@example.com',
    'national_code' => '1234567890',
    'user_type' => 'employee',
    'user_type_label' => 'کارمند',
    'customer_type' => null,
    'gender' => 1,
    'birth_date' => '1985-05-15',
    'is_active' => true,
    'employee_code' => 'EMP001',
    'job_position_id' => 2,
    'job_position' => 'مدیر فروش',
    'direct_manager_id' => 1,
    'direct_manager' => 'محمد رضایی',
    'department_id' => 1,
    'department' => 'فروش',
    'employment_date' => '2020-03-01',
    'image_url' => null,
];

// تنظیم عنوان و breadcrumb بر اساس نوع کاربر
$userType = $user['user_type'];
$typeLabels = [
    'employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'editTitle' => 'ویرایش کارمند'],
    'customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'editTitle' => 'ویرایش مشتری'],
    'user' => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'editTitle' => 'ویرایش کاربر'],
];

$pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['editTitle'] : 'ویرایش کاربر';
$listTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
$listUrl = '/dashboard/org/users/list.php' . ($userType ? '?type=' . $userType : '');
$currentPage = 'organizational-structure';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'مشاهده جزئیات', 'url' => '/dashboard/org/users/view.php?id=' . $userId, 'icon' => 'fa-solid fa-eye', 'type' => 'outline'],
    ['label' => 'بازگشت به لیست', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline']
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
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST" enctype="multipart/form-data">
        
        <!-- اطلاعات پایه -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام کامل <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="full_name" required value="<?= $user['full_name'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام
                </label>
                <input type="text" name="first_name" value="<?= $user['first_name'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام خانوادگی
                </label>
                <input type="text" name="last_name" value="<?= $user['last_name'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  شماره موبایل <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="tel" name="mobile" required dir="ltr" value="<?= $user['mobile'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  ایمیل
                </label>
                <input type="email" name="email" dir="ltr" value="<?= $user['email'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد ملی
                </label>
                <input type="text" name="national_code" maxlength="10" dir="ltr" value="<?= $user['national_code'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  جنسیت
                </label>
                <select name="gender" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1" <?= $user['gender'] == 1 ? 'selected' : '' ?>>مرد</option>
                  <option value="2" <?= $user['gender'] == 2 ? 'selected' : '' ?>>زن</option>
                </select>
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تاریخ تولد
                </label>
                <input type="date" name="birth_date" value="<?= $user['birth_date'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- اطلاعات کاربری -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات کاربری</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نوع کاربر <span class="text-red-500 mr-1">*</span>
                </label>
                <select name="user_type" required class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="1" <?= $user['user_type'] == 1 ? 'selected' : '' ?>>کاربر عادی</option>
                  <option value="2" <?= $user['user_type'] == 2 ? 'selected' : '' ?>>مشتری</option>
                  <option value="3" <?= $user['user_type'] == 3 ? 'selected' : '' ?>>کارمند</option>
                </select>
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  رمز عبور جدید
                </label>
                <input type="password" name="password" dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="خالی بگذارید اگر تغییری نمی‌خواهید">
              </div>
            </div>
            
            <div class="flex items-center gap-6 px-lg py-3.5">
              <label class="text-sm text-text-secondary leading-normal min-w-[140px]">
                وضعیت کاربر
              </label>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">فعال</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="is_active" value="0" <?= !$user['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-primary accent-primary">
                  <span class="text-base text-text-primary leading-normal">غیرفعال</span>
                </label>
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- اطلاعات استخدامی -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات استخدامی</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد پرسنلی
                </label>
                <input type="text" name="employee_code" value="<?= $user['employee_code'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  موقعیت شغلی
                </label>
                <select name="job_position_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1" <?= $user['job_position_id'] == 1 ? 'selected' : '' ?>>مدیر عامل</option>
                  <option value="2" <?= $user['job_position_id'] == 2 ? 'selected' : '' ?>>مدیر فروش</option>
                  <option value="3" <?= $user['job_position_id'] == 3 ? 'selected' : '' ?>>کارشناس فنی</option>
                </select>
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  مدیر مستقیم
                </label>
                <select name="direct_manager_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">بدون مدیر</option>
                  <option value="1" <?= $user['direct_manager_id'] == 1 ? 'selected' : '' ?>>محمد رضایی</option>
                  <option value="2" <?= $user['direct_manager_id'] == 2 ? 'selected' : '' ?>>فاطمه محمدی</option>
                </select>
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  دپارتمان اصلی
                </label>
                <select name="department_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1" <?= $user['department_id'] == 1 ? 'selected' : '' ?>>فروش</option>
                  <option value="2" <?= $user['department_id'] == 2 ? 'selected' : '' ?>>فنی</option>
                  <option value="3" <?= $user['department_id'] == 3 ? 'selected' : '' ?>>منابع انسانی</option>
                </select>
              </div>
            </div>
            
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تاریخ استخدام
                </label>
                <input type="date" name="employment_date" value="<?= $user['employment_date'] ?>"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- تصویر پروفایل -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">تصویر پروفایل</h2>
          
          <div class="flex items-start gap-6">
            <div class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-border-medium">
              <?php if ($user['image_url']): ?>
                <img src="<?= $user['image_url'] ?>" alt="<?= $user['full_name'] ?>" class="w-full h-full rounded-xl object-cover">
              <?php else: ?>
                <i class="fa-solid fa-user text-4xl text-text-muted"></i>
              <?php endif; ?>
            </div>
            <div class="flex-1">
              <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
              <label for="imageInput" class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal cursor-pointer">
                <i class="fa-solid fa-upload"></i>
                <span>تغییر تصویر</span>
              </label>
              <p class="text-sm text-text-muted mt-2 leading-normal">فرمت‌های مجاز: JPG, PNG - حداکثر حجم: 2MB</p>
            </div>
          </div>
        </div>
        
        <!-- دکمه‌های عملیات -->
        <div class="flex items-center gap-3">
          <button type="submit" class="bg-green-600 text-white px-xl py-md rounded-lg font-medium hover:bg-green-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-check ml-2"></i>
            <span>ذخیره تغییرات</span>
          </button>
          <a href="/dashboard/org/users/list.php" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-times ml-2"></i>
            <span>انصراف</span>
          </a>
          <button type="button" class="bg-red-600 text-white px-xl py-md rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal mr-auto">
            <i class="fa-solid fa-trash ml-2"></i>
            <span>حذف کاربر</span>
          </button>
        </div>
        
      </form>
      
      </div>
    </main>
  </div>
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>

