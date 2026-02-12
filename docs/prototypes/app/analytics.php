<?php
/**
 * صفحه آمار و گزارشات
 * نمایش فعالیت‌ها، امتیازات و نمودارها
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'آمار و گزارشات';
$currentTab = 'analytics';

// آمار کلی کاربر
$userAnalytics = [
    'totalPoints' => '۱,۲۸۵',
    'currentLevel' => 'طلایی',
    'nextLevel' => 'پلاتینیوم',
    'pointsToNextLevel' => '۲۱۵',
    'rank' => '۱۸',
    'totalUsers' => '۲۴۷'
];

// فعالیت‌های اخیر
$recentActivities = [
    [
        'type' => 'experience',
        'title' => 'ثبت تجربه جدید',
        'description' => 'بهینه‌سازی فرآیند قراردادها',
        'points' => '+۵۰',
        'date' => 'امروز',
        'time' => '۱۴:۳۰',
        'icon' => 'fa-lightbulb',
        'color' => 'amber'
    ],
    [
        'type' => 'task',
        'title' => 'تکمیل وظیفه',
        'description' => 'بررسی درخواست مرخصی',
        'points' => '+۲۰',
        'date' => 'امروز',
        'time' => '۱۰:۱۵',
        'icon' => 'fa-check-circle',
        'color' => 'green'
    ],
    [
        'type' => 'training',
        'title' => 'اتمام دوره آموزشی',
        'description' => 'مدیریت پروژه Agile',
        'points' => '+۱۰۰',
        'date' => 'دیروز',
        'time' => '۱۶:۴۵',
        'icon' => 'fa-graduation-cap',
        'color' => 'purple'
    ],
    [
        'type' => 'comment',
        'title' => 'نظر روی تجربه',
        'description' => 'کاهش زمان تولید محصول',
        'points' => '+۱۰',
        'date' => 'دیروز',
        'time' => '۱۱:۲۰',
        'icon' => 'fa-comment',
        'color' => 'sky'
    ],
    [
        'type' => 'like',
        'title' => 'دریافت لایک',
        'description' => 'تجربه شما پسندیده شد',
        'points' => '+۵',
        'date' => '۲ روز پیش',
        'time' => '۰۹:۳۰',
        'icon' => 'fa-heart',
        'color' => 'rose'
    ],
];

// داده‌های امتیازات هفته گذشته (برای نمودار)
$weeklyPoints = [
    ['day' => 'ش', 'points' => 45],
    ['day' => 'ی', 'points' => 30],
    ['day' => 'د', 'points' => 75],
    ['day' => 'س', 'points' => 60],
    ['day' => 'چ', 'points' => 90],
    ['day' => 'پ', 'points' => 55],
    ['day' => 'ج', 'points' => 70],
];
$maxPoints = max(array_column($weeklyPoints, 'points'));

// دستاوردها
$achievements = [
    [
        'title' => 'نویسنده فعال',
        'description' => '۱۰ تجربه منتشر شده',
        'icon' => 'fa-pen',
        'unlocked' => true,
        'progress' => 100
    ],
    [
        'title' => 'یادگیرنده',
        'description' => '۵ دوره آموزشی تکمیل شده',
        'icon' => 'fa-book',
        'unlocked' => true,
        'progress' => 100
    ],
    [
        'title' => 'همکار مفید',
        'description' => '۵۰ کامنت مفید',
        'icon' => 'fa-comments',
        'unlocked' => false,
        'progress' => 68
    ],
    [
        'title' => 'قهرمان ماه',
        'description' => 'بیشترین امتیاز ماه',
        'icon' => 'fa-trophy',
        'unlocked' => false,
        'progress' => 45
    ],
];

// آمار تفکیکی
$categoryStats = [
    ['category' => 'ثبت تجربه', 'count' => '۱۲', 'points' => '۶۰۰', 'color' => 'amber'],
    ['category' => 'آموزش', 'count' => '۵', 'points' => '۳۵۰', 'color' => 'purple'],
    ['category' => 'وظایف', 'count' => '۲۴', 'points' => '۲۴۰', 'color' => 'green'],
    ['category' => 'تعامل', 'count' => '۳۸', 'points' => '۹۵', 'color' => 'sky'],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative pb-20">

    <!-- Header -->
    <div class="bg-gradient-to-br from-emerald-900 to-teal-700 px-5 pt-8 pb-8 rounded-b-[32px] shadow-lg">
      <h1 class="text-white text-xl font-bold mb-6 flex items-center gap-2">
        <i class="fa-solid fa-chart-line"></i>
        آمار و گزارشات
      </h1>

      <!-- Progress to Next Level -->
      <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 mb-4">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-trophy text-yellow-900 text-lg"></i>
            </div>
            <div>
              <p class="text-white text-sm font-bold"><?= $userAnalytics['currentLevel'] ?></p>
              <p class="text-white/60 text-xs">سطح فعلی</p>
            </div>
          </div>
          <div class="text-left">
            <p class="text-white text-sm font-bold"><?= $userAnalytics['pointsToNextLevel'] ?></p>
            <p class="text-white/60 text-xs">تا سطح بعد</p>
          </div>
        </div>
        <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
          <?php
          $progress = 85; // محاسبه درصد واقعی
          ?>
          <div class="h-full bg-yellow-400 rounded-full transition-all" style="width: <?= $progress ?>%"></div>
        </div>
        <p class="text-white/70 text-xs mt-2">
          رتبه شما: <?= $userAnalytics['rank'] ?> از <?= $userAnalytics['totalUsers'] ?>
        </p>
      </div>

      <!-- Total Points Card -->
      <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/70 text-xs mb-1">مجموع امتیازات</p>
            <p class="text-white text-3xl font-bold"><?= $userAnalytics['totalPoints'] ?></p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center">
            <i class="fa-solid fa-star text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-8 space-y-8">

      <!-- Weekly Chart -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          امتیازات هفته اخیر
        </h2>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
          <div class="flex items-end justify-between gap-2 h-40">
            <?php foreach ($weeklyPoints as $day): ?>
            <?php
            $height = ($day['points'] / $maxPoints) * 100;
            ?>
            <div class="flex-1 flex flex-col items-center gap-2">
              <div class="relative w-full bg-gray-200 rounded-t-lg transition-all hover:bg-gray-300 cursor-pointer group" style="height: <?= $height ?>%">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-2 py-1 rounded text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                  <?= $day['points'] ?> امتیاز
                </div>
              </div>
              <span class="text-slate-600 text-xs font-medium"><?= $day['day'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Category Stats -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          آمار تفکیکی
        </h2>
        <div class="space-y-3">
          <?php foreach ($categoryStats as $stat): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                  <i class="fa-solid fa-check text-slate-600"></i>
                </div>
                <div>
                  <p class="text-slate-900 text-sm font-semibold"><?= $stat['category'] ?></p>
                  <p class="text-slate-600 text-xs"><?= $stat['count'] ?> فعالیت</p>
                </div>
              </div>
              <div class="text-left">
                <p class="text-slate-900 text-lg font-bold"><?= $stat['points'] ?></p>
                <p class="text-slate-500 text-xs">امتیاز</p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Achievements -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          دستاوردها
        </h2>
        <div class="grid grid-cols-2 gap-3">
          <?php foreach ($achievements as $achievement): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 text-center <?= $achievement['unlocked'] ? '' : 'opacity-60' ?>">
            <div class="w-14 h-14 <?= $achievement['unlocked'] ? 'bg-slate-900' : 'bg-gray-200' ?> rounded-xl flex items-center justify-center mx-auto mb-3">
              <i class="fa-solid <?= $achievement['icon'] ?> <?= $achievement['unlocked'] ? 'text-white' : 'text-gray-400' ?> text-xl"></i>
            </div>
            <h3 class="text-slate-900 text-xs font-bold mb-1"><?= $achievement['title'] ?></h3>
            <p class="text-slate-600 text-xs leading-tight mb-2"><?= $achievement['description'] ?></p>
            <?php if (!$achievement['unlocked']): ?>
            <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
              <div class="h-full bg-slate-900 rounded-full transition-all" style="width: <?= $achievement['progress'] ?>%"></div>
            </div>
            <p class="text-slate-500 text-xs mt-1"><?= $achievement['progress'] ?>٪</p>
            <?php else: ?>
            <div class="flex items-center justify-center gap-1 text-slate-600 text-xs font-medium">
              <i class="fa-solid fa-check-circle"></i>
              کسب شده
            </div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Recent Activities -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">
          فعالیت‌های اخیر
        </h2>
        <div class="space-y-3">
          <?php foreach ($recentActivities as $activity): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid <?= $activity['icon'] ?> text-slate-600"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-1">
                  <h3 class="text-slate-900 text-sm font-semibold leading-tight"><?= $activity['title'] ?></h3>
                  <span class="bg-gray-100 text-slate-900 px-2 py-0.5 rounded-lg text-xs font-bold flex-shrink-0">
                    <?= $activity['points'] ?>
                  </span>
                </div>
                <p class="text-slate-600 text-xs mb-2"><?= $activity['description'] ?></p>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                  <span><?= $activity['date'] ?></span>
                  <span>•</span>
                  <span><?= $activity['time'] ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- Bottom Navigation -->
    <?php component('app-bottom-nav', ['currentTab' => $currentTab]); ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>

</body>
</html>
