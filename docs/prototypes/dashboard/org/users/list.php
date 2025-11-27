<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت نوع کاربر از URL
$userType   = $_GET['type'] ?? '';
$validTypes = ['employee' => 'کارمند', 'customer' => 'مشتری', 'user' => 'کاربر'];

// تنظیم عنوان و breadcrumb بر اساس نوع
$typeLabels = [
    'employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'icon' => 'fa-user-tie'],
    'customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'icon' => 'fa-users'],
    'user'     => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'icon' => 'fa-user'],
];

$pageTitle   = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
$currentPage = 'organizational-structure';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی', 'url' => '/dashboard/org/index.php'],
    ['label' => $pageTitle],
];

// دکمه‌های عملیاتی
$createUrl     = '/dashboard/org/users/create.php' . ($userType ? '?type=' . $userType : '');
$createLabel   = isset($typeLabels[$userType]) ? 'افزودن ' . $typeLabels[$userType]['singular'] . ' جدید' : 'افزودن کاربر جدید';
$actionButtons = [
    ['label' => $createLabel, 'url' => $createUrl, 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// داده‌های نمونه کاربران (در پروژه واقعی از دیتابیس)
$allUsers = [
    ['id' => 1, 'full_name' => 'علی احمدی', 'mobile' => '09121234567', 'email' => 'ali@example.com', 'user_type' => 'کارمند', 'user_type_key' => 'employee', 'job_position' => 'مدیر فروش', 'department' => 'فروش', 'is_active' => true, 'image_url' => null],
    ['id' => 2, 'full_name' => 'فاطمه محمدی', 'mobile' => '09129876543', 'email' => 'fateme@example.com', 'user_type' => 'کارمند', 'user_type_key' => 'employee', 'job_position' => 'کارشناس فنی', 'department' => 'فنی', 'is_active' => true, 'image_url' => null],
    ['id' => 3, 'full_name' => 'محمد رضایی', 'mobile' => '09131112233', 'email' => 'mohammad@example.com', 'user_type' => 'مشتری', 'user_type_key' => 'customer', 'job_position' => '-', 'department' => '-', 'is_active' => true, 'image_url' => null],
    ['id' => 4, 'full_name' => 'زهرا حسینی', 'mobile' => '09141234567', 'email' => 'zahra@example.com', 'user_type' => 'کارمند', 'user_type_key' => 'employee', 'job_position' => 'منابع انسانی', 'department' => 'منابع انسانی', 'is_active' => false, 'image_url' => null],
    ['id' => 5, 'full_name' => 'حسین کریمی', 'mobile' => '09151234567', 'email' => null, 'user_type' => 'کاربر', 'user_type_key' => 'user', 'job_position' => '-', 'department' => '-', 'is_active' => true, 'image_url' => null],
    ['id' => 6, 'full_name' => 'مریم عباسی', 'mobile' => '09161234567', 'email' => 'maryam@example.com', 'user_type' => 'مشتری', 'user_type_key' => 'customer', 'job_position' => '-', 'department' => '-', 'is_active' => true, 'image_url' => null],
    ['id' => 7, 'full_name' => 'رضا نوری', 'mobile' => '09171234567', 'email' => 'reza@example.com', 'user_type' => 'کارمند', 'user_type_key' => 'employee', 'job_position' => 'مدیر مالی', 'department' => 'مالی', 'is_active' => true, 'image_url' => null],
];

// فیلتر کاربران بر اساس نوع
if ($userType && isset($validTypes[$userType])) {
    $users = array_filter($allUsers, fn ($u) => $u['user_type_key'] === $userType);
} else {
    $users = $allUsers;
}

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
                     placeholder="نام، موبایل یا ایمیل">
            </div>
          </div>
          
          <?php if ($userType === 'employee'): ?>
          <!-- دپارتمان (فقط برای کارکنان) -->
          <div class="flex-1 min-w-[200px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                دپارتمان
              </label>
              <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option>همه دپارتمان‌ها</option>
                <option>فروش</option>
                <option>فنی</option>
                <option>منابع انسانی</option>
                <option>مالی</option>
              </select>
            </div>
          </div>
          <?php endif; ?>
          
          <!-- وضعیت -->
          <div class="flex-1 min-w-[180px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                وضعیت
              </label>
              <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                <option>همه</option>
                <option>فعال</option>
                <option>غیرفعال</option>
              </select>
            </div>
          </div>
          
          <!-- دکمه‌های عملیاتی -->
          <div class="flex items-center gap-2">
            <button class="bg-primary text-white px-xl py-3.5 rounded-lg font-medium hover:bg-blue-700 transition-all duration-200 text-base leading-normal whitespace-nowrap">
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
      
      <!-- جدول کاربران -->
      <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal"><?= $userType === 'employee' ? 'کارمند' : ($userType === 'customer' ? 'مشتری' : 'کاربر') ?></th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">شماره موبایل</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">ایمیل</th>
                <?php if (!$userType): ?>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">نوع کاربر</th>
                <?php endif; ?>
                <?php if ($userType === 'employee' || !$userType): ?>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">موقعیت شغلی</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">دپارتمان</th>
                <?php endif; ?>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                        <?php if ($user['image_url']): ?>
                          <img src="<?= $user['image_url'] ?>" alt="<?= $user['full_name'] ?>" class="w-full h-full rounded-full object-cover">
                        <?php else: ?>
                          <span class="text-primary font-semibold text-base"><?= mb_substr($user['full_name'], 0, 1) ?></span>
                        <?php endif; ?>
                      </div>
                      <span class="text-base text-text-primary font-medium leading-normal"><?= $user['full_name'] ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal" dir="ltr"><?= $user['mobile'] ?></td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $user['email'] ?? '-' ?></td>
                  <?php if (!$userType): ?>
                  <td class="px-6 py-4">
                    <?php
                    $typeColors = [
                        'کارمند' => 'bg-green-50 text-green-700',
                        'مشتری'  => 'bg-blue-50 text-blue-700',
                        'کاربر'  => 'bg-gray-100 text-gray-700',
                    ];
                      $typeColor = $typeColors[$user['user_type']] ?? 'bg-gray-100 text-gray-700';
                      ?>
                    <span class="inline-flex items-center gap-1.5 <?= $typeColor ?> px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <?= $user['user_type'] ?>
                    </span>
                  </td>
                  <?php endif; ?>
                  <?php if ($userType === 'employee' || !$userType): ?>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $user['job_position'] ?></td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $user['department'] ?></td>
                  <?php endif; ?>
                  <td class="px-6 py-4">
                    <?php if ($user['is_active']): ?>
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
                      <?php if ($user['user_type_key'] === 'employee'): ?>
                      <button onclick="openAccessModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>')" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-amber-600 hover:bg-amber-50 rounded transition-all duration-200" title="تنظیم سطح دسترسی">
                        <i class="fa-solid fa-user-shield"></i>
                      </button>
                      <?php endif; ?>
                      <a href="/dashboard/org/users/view.php?id=<?= $user['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="مشاهده جزئیات">
                        <i class="fa-solid fa-eye"></i>
                      </a>
                      <a href="/dashboard/org/users/edit.php?id=<?= $user['id'] ?>" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                        <i class="fa-solid fa-pen"></i>
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
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-border-light flex items-center justify-between">
          <div class="text-sm text-text-secondary leading-normal">
            <?php
            $countLabel = $userType === 'employee' ? 'کارمند' : ($userType === 'customer' ? 'مشتری' : ($userType === 'user' ? 'کاربر' : 'کاربر'));
$totalCount             = count($users);
?>
            نمایش <span class="font-semibold text-text-primary">1</span> تا <span class="font-semibold text-text-primary"><?= $totalCount ?></span> از <span class="font-semibold text-text-primary"><?= $totalCount ?></span> <?= $countLabel ?>
          </div>
          <div class="flex items-center gap-2">
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium">1</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
          </div>
        </div>
      </div>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- مودال تنظیم سطح دسترسی -->
  <div id="accessModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-user-shield text-amber-600"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-text-primary leading-snug">تنظیم سطح دسترسی</h3>
            <p class="text-sm text-text-secondary leading-normal" id="accessModalUserName"></p>
          </div>
        </div>
        <button onclick="closeAccessModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      
      <!-- Body -->
      <div class="p-6 space-y-6">
        <!-- نقش کاربری اصلی -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[130px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              <i class="fa-solid fa-star text-amber-500 ml-2"></i>
              نقش اصلی
            </label>
            <select id="primaryRole" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
              <option value="">انتخاب کنید</option>
              <option value="1">مدیر سیستم</option>
              <option value="2">مدیر ارشد</option>
              <option value="3">مدیر فروش</option>
              <option value="4">کارشناس فروش</option>
              <option value="5">کارشناس فنی</option>
              <option value="6">منابع انسانی</option>
              <option value="7">مدیر مالی</option>
              <option value="8">کارشناس مالی</option>
              <option value="9">مدیر مارکتینگ</option>
              <option value="10">کارشناس مارکتینگ</option>
              <option value="11">کارشناس CRM</option>
            </select>
          </div>
        </div>
        
        <!-- نقش‌های کاربری جانبی -->
        <div>
          <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
            <i class="fa-solid fa-tags text-slate-400 ml-2"></i>
            نقش‌های جانبی
            <span class="text-text-muted text-xs">(چند انتخابی)</span>
          </label>
          <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary space-y-3 max-h-[200px] overflow-y-auto">
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="1">
              <span class="text-sm text-text-primary leading-normal">مدیر سیستم</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="2">
              <span class="text-sm text-text-primary leading-normal">مدیر ارشد</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="3">
              <span class="text-sm text-text-primary leading-normal">مدیر فروش</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="4">
              <span class="text-sm text-text-primary leading-normal">کارشناس فروش</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="5">
              <span class="text-sm text-text-primary leading-normal">کارشناس فنی</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="6">
              <span class="text-sm text-text-primary leading-normal">منابع انسانی</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="7">
              <span class="text-sm text-text-primary leading-normal">مدیر مالی</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="8">
              <span class="text-sm text-text-primary leading-normal">کارشناس مالی</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="9">
              <span class="text-sm text-text-primary leading-normal">مدیر مارکتینگ</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="10">
              <span class="text-sm text-text-primary leading-normal">کارشناس مارکتینگ</span>
            </label>
            <label class="flex items-center gap-3 p-2 hover:bg-white rounded-lg cursor-pointer transition-all duration-200">
              <input type="checkbox" class="w-4 h-4 text-primary border-border-medium rounded focus:ring-primary focus:ring-offset-0" value="11">
              <span class="text-sm text-text-primary leading-normal">کارشناس CRM</span>
            </label>
          </div>
          <p class="text-xs text-text-muted mt-2 leading-normal">
            <i class="fa-solid fa-info-circle ml-1"></i>
            نقش‌های جانبی دسترسی‌های اضافی به کاربر اعطا می‌کنند.
          </p>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 bg-bg-secondary rounded-b-2xl">
        <button onclick="closeAccessModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button onclick="saveAccessSettings()" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-all duration-200 text-base leading-normal">
          <i class="fa-solid fa-check ml-2"></i>
          ذخیره تغییرات
        </button>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    // متغیر برای نگهداری ID کاربر فعلی
    let currentUserId = null;
    
    // باز کردن مودال تنظیم دسترسی
    function openAccessModal(userId, userName) {
      currentUserId = userId;
      document.getElementById('accessModalUserName').textContent = userName;
      document.getElementById('accessModal').classList.remove('hidden');
      document.getElementById('accessModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    // بستن مودال
    function closeAccessModal() {
      document.getElementById('accessModal').classList.add('hidden');
      document.getElementById('accessModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      currentUserId = null;
      
      // ریست فرم
      document.getElementById('primaryRole').value = '';
      document.querySelectorAll('#accessModal input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
    
    // ذخیره تنظیمات دسترسی
    function saveAccessSettings() {
      const primaryRole = document.getElementById('primaryRole').value;
      const secondaryRoles = [];
      document.querySelectorAll('#accessModal input[type="checkbox"]:checked').forEach(cb => {
        secondaryRoles.push(cb.value);
      });
      
      // در پروژه واقعی اینجا باید AJAX request ارسال شود
      console.log('User ID:', currentUserId);
      console.log('Primary Role:', primaryRole);
      console.log('Secondary Roles:', secondaryRoles);
      
      // نمایش پیام موفقیت (در پروژه واقعی)
      alert('تغییرات با موفقیت ذخیره شد');
      closeAccessModal();
    }
    
    // بستن مودال با کلیک بیرون از آن
    document.getElementById('accessModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeAccessModal();
      }
    });
    
    // بستن مودال با کلید Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !document.getElementById('accessModal').classList.contains('hidden')) {
        closeAccessModal();
      }
    });
  </script>
  
</body>
</html>

