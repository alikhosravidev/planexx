<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت نوع کاربر از URL
$userType = $_GET['type'] ?? '';
$validTypes = ['employee' => 'کارمند', 'customer' => 'مشتری', 'user' => 'کاربر'];

// تنظیم عنوان و breadcrumb بر اساس نوع
$typeLabels = [
    'employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'createTitle' => 'افزودن کارمند جدید'],
    'customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'createTitle' => 'افزودن مشتری جدید'],
    'user' => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'createTitle' => 'افزودن کاربر جدید'],
];

$pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['createTitle'] : 'افزودن کاربر جدید';
$listTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
$listUrl = '/dashboard/org/users/list.php' . ($userType ? '?type=' . $userType : '');
$currentPage = 'organizational-structure';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => $listTitle, 'url' => $listUrl],
    ['label' => $pageTitle],
];

$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline']
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('org-sidebar', ['currentPage' => $currentPage]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Module Header -->
      <?php component('module-header', [
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST" enctype="multipart/form-data">
        
        <!-- اطلاعات پایه -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- نام کامل -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام کامل <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" name="full_name" required
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="نام و نام خانوادگی">
              </div>
            </div>
            
            <!-- نام -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام
                </label>
                <input type="text" name="first_name"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="نام">
              </div>
            </div>
            
            <!-- نام خانوادگی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نام خانوادگی
                </label>
                <input type="text" name="last_name"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="نام خانوادگی">
              </div>
            </div>
            
            <!-- شماره موبایل -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  شماره موبایل <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="tel" name="mobile" required dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="09121234567">
              </div>
            </div>
            
            <!-- ایمیل -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  ایمیل
                </label>
                <input type="email" name="email" dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="example@domain.com">
              </div>
            </div>
            
            <!-- کد ملی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد ملی
                </label>
                <input type="text" name="national_code" maxlength="10" dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="1234567890">
              </div>
            </div>
            
            <!-- جنسیت -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  جنسیت
                </label>
                <select name="gender" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1">مرد</option>
                  <option value="2">زن</option>
                </select>
              </div>
            </div>
            
            <!-- تاریخ تولد -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تاریخ تولد
                </label>
                <input type="date" name="birth_date"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- اطلاعات کاربری -->
        <div class="bg-white border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات کاربری</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- نوع کاربر -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نوع کاربر <span class="text-red-500 mr-1">*</span>
                </label>
                <select name="user_type" required class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal <?= $userType ? 'bg-bg-secondary' : '' ?>" <?= $userType ? 'disabled' : '' ?>>
                  <option value="">انتخاب کنید</option>
                  <option value="user" <?= $userType === 'user' ? 'selected' : '' ?>>کاربر عادی</option>
                  <option value="customer" <?= $userType === 'customer' ? 'selected' : '' ?>>مشتری</option>
                  <option value="employee" <?= $userType === 'employee' ? 'selected' : '' ?>>کارمند</option>
                </select>
                <?php if ($userType): ?>
                <input type="hidden" name="user_type" value="<?= htmlspecialchars($userType) ?>">
                <?php endif; ?>
              </div>
            </div>
            
            <?php if ($userType === 'customer' || !$userType): ?>
            <!-- نوع مشتری -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200" data-show-for="customer">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  نوع مشتری
                </label>
                <select name="customer_type" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1">حقیقی</option>
                  <option value="2">حقوقی</option>
                </select>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- رمز عبور -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  رمز عبور
                </label>
                <input type="password" name="password" dir="ltr"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="حداقل 8 کاراکتر">
              </div>
            </div>
            
            <!-- وضعیت -->
            <div class="flex items-center gap-6 px-lg py-3.5">
              <label class="text-sm text-text-secondary leading-normal min-w-[140px]">
                وضعیت کاربر
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
        
        <?php if ($userType === 'employee' || !$userType): ?>
        <!-- اطلاعات استخدامی (فقط برای کارمندان) -->
        <div class="bg-white border border-border-light rounded-2xl p-6 mb-6" data-show-for="employee">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات استخدامی</h2>
          
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- کد پرسنلی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  کد پرسنلی
                </label>
                <input type="text" name="employee_code"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="کد پرسنلی">
              </div>
            </div>
            
            <!-- موقعیت شغلی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  موقعیت شغلی
                </label>
                <select name="job_position_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1">مدیر عامل</option>
                  <option value="2">مدیر فروش</option>
                  <option value="3">کارشناس فنی</option>
                  <option value="4">کارشناس منابع انسانی</option>
                </select>
              </div>
            </div>
            
            <!-- مدیر مستقیم -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  مدیر مستقیم
                </label>
                <select name="direct_manager_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">بدون مدیر</option>
                  <option value="1">علی احمدی</option>
                  <option value="2">فاطمه محمدی</option>
                </select>
              </div>
            </div>
            
            <!-- دپارتمان اصلی -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  دپارتمان اصلی
                </label>
                <select name="department_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option value="">انتخاب کنید</option>
                  <option value="1">فروش</option>
                  <option value="2">فنی</option>
                  <option value="3">منابع انسانی</option>
                  <option value="4">مالی</option>
                </select>
              </div>
            </div>
            
            <!-- تاریخ استخدام -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تاریخ استخدام
                </label>
                <input type="date" name="employment_date"
                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
              </div>
            </div>
            
          </div>
        </div>
        <?php endif; ?>
        
        <!-- تصویر پروفایل -->
        <div class="bg-white border border-border-light rounded-2xl p-6 mb-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">تصویر پروفایل</h2>
          
          <div class="flex items-start gap-6">
            <div class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-border-medium">
              <i class="fa-solid fa-user text-4xl text-text-muted"></i>
            </div>
            <div class="flex-1">
              <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
              <label for="imageInput" class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal cursor-pointer">
                <i class="fa-solid fa-upload"></i>
                <span>انتخاب تصویر</span>
              </label>
              <p class="text-sm text-text-muted mt-2 leading-normal">فرمت‌های مجاز: JPG, PNG - حداکثر حجم: 2MB</p>
            </div>
          </div>
        </div>
        
        <!-- دکمه‌های عملیات -->
        <div class="flex items-center gap-3">
          <button type="submit" class="bg-green-600 text-white px-xl py-md rounded-lg font-medium hover:bg-green-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-check ml-2"></i>
            <span>ذخیره اطلاعات</span>
          </button>
          <a href="<?= $listUrl ?>" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-times ml-2"></i>
            <span>انصراف</span>
          </a>
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

