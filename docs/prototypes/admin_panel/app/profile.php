<?php
/**
 * صفحه پروفایل کاربری
 * نمایش اطلاعات کامل کاربر و دکمه خروج
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'پروفایل';
$currentTab = 'profile';

// اطلاعات کاربر
$userProfile = [
    'name'       => 'محمدرضا احمدی',
    'email'      => 'mohammadrezaahmadi@example.com',
    'phone'      => '۰۹۱۲۳۴۵۶۷۸۹',
    'employeeId' => 'EMP-۱۲۳۴',
    'department' => 'فناوری اطلاعات',
    'position'   => 'مدیر پروژه ارشد',
    'joinDate'   => '۱۴۰۲/۰۱/۱۵',
    'level'      => 'طلایی',
    'points'     => '۱,۲۸۵',
    'avatar'     => 'https://picsum.photos/seed/avatar1/200/200',
];

// تنظیمات حساب کاربری
$accountSettings = [
    [
        'title'       => 'اطلاعات شخصی',
        'description' => 'نام، ایمیل و شماره تماس',
        'icon'        => 'fa-user',
        'color'       => 'blue',
        'action'      => '#',
    ],
    [
        'title'       => 'رمز عبور',
        'description' => 'تغییر رمز عبور حساب کاربری',
        'icon'        => 'fa-lock',
        'color'       => 'purple',
        'action'      => '#',
    ],
    [
        'title'       => 'اعلان‌ها',
        'description' => 'مدیریت اعلان‌های دریافتی',
        'icon'        => 'fa-bell',
        'color'       => 'amber',
        'action'      => '#',
    ],
    [
        'title'       => 'علایق',
        'description' => 'ویرایش تگ‌ها و علایق شما',
        'icon'        => 'fa-tags',
        'color'       => 'green',
        'action'      => '#',
    ],
];

// آمار فعالیت کاربر
$userStats = [
    [
        'title' => 'تجربیات ثبت شده',
        'value' => '۱۲',
        'icon'  => 'fa-lightbulb',
        'color' => 'amber',
    ],
    [
        'title' => 'دوره‌های آموزشی',
        'value' => '۵',
        'icon'  => 'fa-graduation-cap',
        'color' => 'purple',
    ],
    [
        'title' => 'وظایف تکمیل شده',
        'value' => '۲۴',
        'icon'  => 'fa-check-circle',
        'color' => 'green',
    ],
    [
        'title' => 'نظرات ارسالی',
        'value' => '۳۸',
        'icon'  => 'fa-comments',
        'color' => 'sky',
    ],
];

// تاریخچه سطح کاربر
$levelHistory = [
    ['level' => 'برنزی', 'date' => '۱۴۰۲/۰۱/۱۵', 'points' => '۰'],
    ['level' => 'نقره‌ای', 'date' => '۱۴۰۲/۰۳/۱۰', 'points' => '۵۰۰'],
    ['level' => 'طلایی', 'date' => '۱۴۰۲/۰۶/۲۵', 'points' => '۱,۰۰۰'],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative pb-20">

    <!-- Header with Profile Cover -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-700 h-40 rounded-b-[32px] relative">
      <!-- Pattern Overlay -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
      </div>
    </div>

    <!-- Profile Card (Overlapping) -->
    <div class="px-5 -mt-24">
      <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
        <!-- Avatar and Basic Info -->
        <div class="flex flex-col items-center mb-6">
          <div class="relative mb-4">
            <img src="<?= $userProfile['avatar'] ?>" alt="<?= $userProfile['name'] ?>" class="w-24 h-24 rounded-2xl shadow-lg">
            <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
              <i class="fa-solid fa-trophy text-white text-lg"></i>
            </div>
          </div>
          <h1 class="text-slate-900 text-xl font-bold mb-1"><?= $userProfile['name'] ?></h1>
          <p class="text-slate-600 text-sm mb-1"><?= $userProfile['position'] ?></p>
          <p class="text-slate-500 text-xs"><?= $userProfile['department'] ?></p>
        </div>

        <!-- Level and Points -->
        <div class="flex items-center gap-3 mb-6">
          <div class="flex-1 bg-amber-50 border border-amber-200 rounded-2xl p-3 text-center">
            <div class="flex items-center justify-center gap-1.5 mb-1">
              <i class="fa-solid fa-star text-amber-600 text-sm"></i>
              <p class="text-amber-900 text-lg font-bold"><?= $userProfile['points'] ?></p>
            </div>
            <p class="text-amber-700 text-xs">امتیاز کل</p>
          </div>
          <div class="flex-1 bg-purple-50 border border-purple-200 rounded-2xl p-3 text-center">
            <div class="flex items-center justify-center gap-1.5 mb-1">
              <i class="fa-solid fa-trophy text-purple-600 text-sm"></i>
              <p class="text-purple-900 text-lg font-bold"><?= $userProfile['level'] ?></p>
            </div>
            <p class="text-purple-700 text-xs">سطح فعلی</p>
          </div>
        </div>

        <!-- Employee Info Grid -->
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="flex items-center gap-2 mb-1">
              <i class="fa-solid fa-id-card text-slate-400 text-xs"></i>
              <p class="text-slate-500 text-xs">کد پرسنلی</p>
            </div>
            <p class="text-slate-900 text-sm font-semibold"><?= $userProfile['employeeId'] ?></p>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <div class="flex items-center gap-2 mb-1">
              <i class="fa-solid fa-calendar text-slate-400 text-xs"></i>
              <p class="text-slate-500 text-xs">تاریخ عضویت</p>
            </div>
            <p class="text-slate-900 text-sm font-semibold"><?= $userProfile['joinDate'] ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-8 space-y-8">

      <!-- User Stats -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          آمار فعالیت
        </h2>
        <div class="grid grid-cols-2 gap-3">
          <?php foreach ($userStats as $stat): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-gray-300 transition-all">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
              <i class="fa-solid <?= $stat['icon'] ?> text-slate-600 text-lg"></i>
            </div>
            <p class="text-slate-900 text-2xl font-bold mb-1"><?= $stat['value'] ?></p>
            <p class="text-slate-600 text-xs leading-tight"><?= $stat['title'] ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Contact Information -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          اطلاعات تماس
        </h2>
        <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
          <!-- Email -->
          <div class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-envelope text-slate-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-slate-500 text-xs mb-0.5">ایمیل</p>
              <p class="text-slate-900 text-sm font-medium truncate"><?= $userProfile['email'] ?></p>
            </div>
          </div>
          <!-- Phone -->
          <div class="p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-phone text-slate-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-slate-500 text-xs mb-0.5">موبایل</p>
              <p class="text-slate-900 text-sm font-medium"><?= $userProfile['phone'] ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Account Settings -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          تنظیمات حساب
        </h2>
        <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
          <?php foreach ($accountSettings as $setting): ?>
          <a href="<?= $setting['action'] ?>" class="p-4 flex items-center gap-3 hover:bg-gray-50 transition-all active:bg-gray-100">
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid <?= $setting['icon'] ?> text-slate-600"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-slate-900 text-sm font-semibold mb-0.5"><?= $setting['title'] ?></p>
              <p class="text-slate-500 text-xs"><?= $setting['description'] ?></p>
            </div>
            <i class="fa-solid fa-chevron-left text-slate-400 text-sm flex-shrink-0"></i>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Level History -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          تاریخچه سطوح
        </h2>
        <div class="space-y-3">
          <?php foreach ($levelHistory as $index => $history): ?>
          <div class="relative">
            <?php if ($index < count($levelHistory) - 1): ?>
            <div class="absolute right-[19px] top-10 bottom-0 w-0.5 bg-gradient-to-b from-gray-300 to-gray-200"></div>
            <?php endif; ?>
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm relative z-10">
                <i class="fa-solid fa-trophy text-white"></i>
              </div>
              <div class="flex-1 bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
                <h3 class="text-slate-900 text-sm font-bold mb-1"><?= $history['level'] ?></h3>
                <div class="flex items-center justify-between text-xs text-slate-500">
                  <span><?= $history['date'] ?></span>
                  <span class="text-slate-900 font-semibold"><?= $history['points'] ?> امتیاز</span>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Logout Button -->
      <div class="pt-4">
        <button onclick="confirmLogout()" class="w-full bg-red-600 text-white py-4 rounded-2xl font-bold text-base hover:bg-red-700 active:bg-red-800 transition-all flex items-center justify-center gap-2 shadow-lg">
          <i class="fa-solid fa-right-from-bracket"></i>
          خروج از حساب کاربری
        </button>
      </div>

    </div>

    <!-- Bottom Navigation -->
    <?php component('app-bottom-nav', ['currentTab' => $currentTab]); ?>

  </div>

  <!-- Logout Confirmation Modal -->
  <div id="logoutModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[100]">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl">
      <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i class="fa-solid fa-right-from-bracket text-red-600 text-2xl"></i>
      </div>
      <h3 class="text-slate-900 text-lg font-bold text-center mb-2">خروج از حساب؟</h3>
      <p class="text-slate-600 text-sm text-center mb-6 leading-relaxed">
        آیا مطمئن هستید که می‌خواهید از حساب کاربری خود خارج شوید؟
      </p>
      <div class="flex gap-3">
        <button onclick="closeLogoutModal()" class="flex-1 bg-gray-100 text-slate-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all">
          انصراف
        </button>
        <button onclick="logout()" class="flex-1 bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition-all">
          خروج
        </button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script>
    function confirmLogout() {
      document.getElementById('logoutModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
      document.getElementById('logoutModal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function logout() {
      // در اینجا باید لاجیک خروج واقعی قرار گیرد
      // مثلاً: redirect به صفحه لاگین
      window.location.href = '../auth.php';
    }

    // بستن Modal با کلیک روی backdrop
    document.getElementById('logoutModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeLogoutModal();
      }
    });
  </script>

</body>
</html>
