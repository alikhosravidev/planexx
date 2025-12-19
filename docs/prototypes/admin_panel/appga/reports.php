<?php
/**
 * صفحه گزارش‌ها و عملکرد
 * نمایش آمار، امتیازات و گزارش عملکرد کاربر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'گزارش عملکرد';
$currentTab = 'reports';

// اطلاعات کاربر
$user = [
    'name'       => 'محمدرضا احمدی',
    'department' => 'فناوری اطلاعات',
    'position'   => 'کارشناس ارشد',
];

// آمار کلی
$overallStats = [
    'score'          => '۸۷',
    'scoreLabel'     => 'عالی',
    'rank'           => '۵',
    'totalEmployees' => '۴۸',
    'tasksCompleted' => '۱۵۶',
    'onTimeRate'     => '۹۲%',
];

// امتیازات
$pointsData = [
    'total'     => '۲,۴۸۵',
    'thisMonth' => '+۳۲۰',
    'level'     => 'طلایی',
    'nextLevel' => 'پلاتینیوم',
    'progress'  => 72,
];

// آمار تفکیکی
$detailedStats = [
    [
        'title'  => 'وظایف تکمیل شده',
        'value'  => '۱۵۶',
        'change' => '+۱۲',
        'trend'  => 'up',
        'icon'   => 'fa-check-circle',
        'color'  => 'green',
    ],
    [
        'title'  => 'به موقع انجام شده',
        'value'  => '۱۴۴',
        'change' => '۹۲%',
        'trend'  => 'up',
        'icon'   => 'fa-clock',
        'color'  => 'blue',
    ],
    [
        'title'  => 'مشارکت در جلسات',
        'value'  => '۲۸',
        'change' => '+۴',
        'trend'  => 'up',
        'icon'   => 'fa-users',
        'color'  => 'purple',
    ],
    [
        'title'  => 'ثبت تجربه',
        'value'  => '۸',
        'change' => '+۲',
        'trend'  => 'up',
        'icon'   => 'fa-lightbulb',
        'color'  => 'amber',
    ],
];

// نمودار عملکرد ماهانه
$monthlyPerformance = [
    ['month' => 'مهر', 'score' => 78],
    ['month' => 'آبان', 'score' => 82],
    ['month' => 'آذر', 'score' => 87],
];
$maxScore = 100;

// دستاوردها
$achievements = [
    [
        'title'       => 'کارمند نمونه ماه',
        'description' => 'بهترین عملکرد در آذرماه',
        'icon'        => 'fa-trophy',
        'color'       => 'amber',
        'date'        => 'آذر ۱۴۰۳',
        'unlocked'    => true,
    ],
    [
        'title'       => 'بدون تأخیر',
        'description' => '۳۰ روز متوالی بدون تأخیر',
        'icon'        => 'fa-medal',
        'color'       => 'green',
        'date'        => 'آبان ۱۴۰۳',
        'unlocked'    => true,
    ],
    [
        'title'       => 'همکار فعال',
        'description' => '۵۰ مشارکت در جلسات',
        'icon'        => 'fa-star',
        'color'       => 'purple',
        'date'        => 'در حال پیشرفت',
        'unlocked'    => false,
        'progress'    => 56,
    ],
    [
        'title'       => 'مربی',
        'description' => 'آموزش ۵ همکار جدید',
        'icon'        => 'fa-chalkboard-teacher',
        'color'       => 'sky',
        'date'        => 'در حال پیشرفت',
        'unlocked'    => false,
        'progress'    => 40,
    ],
];

// بازخوردهای اخیر
$feedbacks = [
    [
        'from'     => 'علی رضایی',
        'position' => 'مدیر تیم',
        'message'  => 'عملکرد عالی در پروژه اخیر. ادامه بده!',
        'date'     => '۳ روز پیش',
        'rating'   => 5,
    ],
    [
        'from'     => 'سارا محمدی',
        'position' => 'همکار تیم',
        'message'  => 'همکاری خوبی در جلسات داشتید.',
        'date'     => 'هفته پیش',
        'rating'   => 4,
    ],
];

// رنگ‌ها
$colorClasses = [
    'green'  => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
    'blue'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
    'amber'  => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
    'sky'    => ['bg' => 'bg-sky-50', 'text' => 'text-sky-600', 'border' => 'border-sky-200'],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-white">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-24">

    <!-- Header -->
    <div class="bg-gradient-to-br from-emerald-900 to-teal-700 px-5 pt-8 pb-6 rounded-b-[32px] shadow-lg">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-white text-xl font-bold flex items-center gap-2">
          <i class="fa-solid fa-chart-pie"></i>
          گزارش عملکرد
        </h1>
        <button class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm hover:bg-white/20 transition-all">
          <i class="fa-solid fa-download text-white"></i>
        </button>
      </div>

      <!-- Score Card -->
      <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-white/70 text-xs mb-1">امتیاز کلی عملکرد</p>
            <div class="flex items-baseline gap-2">
              <span class="text-white text-4xl font-bold"><?= $overallStats['score'] ?></span>
              <span class="text-white/60 text-sm">از ۱۰۰</span>
            </div>
          </div>
          <div class="w-20 h-20 relative">
            <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="3"/>
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#fbbf24" stroke-width="3" stroke-dasharray="87, 100"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
              <span class="text-yellow-400 text-sm font-bold"><?= $overallStats['scoreLabel'] ?></span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-4 text-white/80 text-sm">
          <div class="flex items-center gap-1.5">
            <i class="fa-solid fa-ranking-star text-yellow-400"></i>
            <span>رتبه <?= $overallStats['rank'] ?> از <?= $overallStats['totalEmployees'] ?></span>
          </div>
          <div class="flex items-center gap-1.5">
            <i class="fa-solid fa-check-double text-green-400"></i>
            <span><?= $overallStats['onTimeRate'] ?> به موقع</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6 space-y-6">

      <!-- Points Section -->
      <div class="bg-gradient-to-br from-slate-900 to-slate-700 rounded-2xl p-5 text-white">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-white/70 text-xs mb-1">مجموع امتیازات</p>
            <div class="flex items-baseline gap-2">
              <span class="text-2xl font-bold"><?= $pointsData['total'] ?></span>
              <span class="text-green-400 text-sm font-medium"><?= $pointsData['thisMonth'] ?> این ماه</span>
            </div>
          </div>
          <div class="flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2">
            <i class="fa-solid fa-trophy text-yellow-400"></i>
            <span class="text-sm font-medium"><?= $pointsData['level'] ?></span>
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between text-xs text-white/60 mb-2">
            <span>پیشرفت به سمت <?= $pointsData['nextLevel'] ?></span>
            <span><?= toPersianNum($pointsData['progress']) ?>%</span>
          </div>
          <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full transition-all" style="width: <?= $pointsData['progress'] ?>%"></div>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">آمار تفکیکی</h2>
        <div class="grid grid-cols-2 gap-3">
          <?php foreach ($detailedStats as $stat): ?>
          <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center <?= $colorClasses[$stat['color']]['bg'] ?>">
                <i class="fa-solid <?= $stat['icon'] ?> <?= $colorClasses[$stat['color']]['text'] ?>"></i>
              </div>
              <div class="flex items-center gap-1 <?= $stat['trend'] === 'up' ? 'text-green-600' : 'text-red-600' ?> text-xs font-medium">
                <i class="fa-solid <?= $stat['trend']                === 'up' ? 'fa-arrow-up' : 'fa-arrow-down' ?> text-[10px]"></i>
                <span><?= $stat['change'] ?></span>
              </div>
            </div>
            <p class="text-slate-900 text-xl font-bold mb-1"><?= $stat['value'] ?></p>
            <p class="text-slate-500 text-xs leading-tight"><?= $stat['title'] ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Monthly Chart -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">روند عملکرد ماهانه</h2>
        <div class="bg-white rounded-2xl p-5 shadow-sm">
          <div class="flex items-end justify-around gap-4 h-32">
            <?php foreach ($monthlyPerformance as $month): ?>
            <?php $height = ($month['score'] / $maxScore) * 100; ?>
            <div class="flex flex-col items-center gap-2 flex-1">
              <span class="text-slate-900 text-sm font-bold"><?= toPersianNum($month['score']) ?>%</span>
              <div class="w-full bg-gray-100 rounded-lg overflow-hidden h-20 flex flex-col justify-end">
                <div class="bg-gradient-to-t from-emerald-600 to-emerald-400 rounded-lg transition-all" style="height: <?= $height ?>%"></div>
              </div>
              <span class="text-slate-600 text-xs font-medium"><?= $month['month'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Achievements -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">دستاوردها</h2>
          <span class="text-slate-500 text-xs"><?= toPersianNum(count(array_filter($achievements, fn ($a) => $a['unlocked']))) ?> از <?= toPersianNum(count($achievements)) ?></span>
        </div>
        <div class="space-y-3">
          <?php foreach ($achievements as $achievement): ?>
          <div class="bg-white rounded-2xl p-4 flex items-center gap-4 <?= !$achievement['unlocked'] ? 'opacity-60' : '' ?> shadow-sm hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $colorClasses[$achievement['color']]['bg'] ?> <?= !$achievement['unlocked'] ? 'grayscale' : '' ?>">
              <i class="fa-solid <?= $achievement['icon'] ?> text-xl <?= $colorClasses[$achievement['color']]['text'] ?>"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <h3 class="text-slate-900 text-sm font-semibold"><?= $achievement['title'] ?></h3>
                <?php if ($achievement['unlocked']): ?>
                <i class="fa-solid fa-circle-check text-green-500 text-xs"></i>
                <?php endif; ?>
              </div>
              <p class="text-slate-500 text-xs mb-1"><?= $achievement['description'] ?></p>
              <?php if (!$achievement['unlocked'] && isset($achievement['progress'])): ?>
              <div class="flex items-center gap-2">
                <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full <?= $colorClasses[$achievement['color']]['bg'] === 'bg-amber-50' ? 'bg-amber-500' : 'bg-' . $achievement['color'] . '-500' ?> rounded-full" style="width: <?= $achievement['progress'] ?>%"></div>
                </div>
                <span class="text-slate-500 text-xs"><?= toPersianNum($achievement['progress']) ?>%</span>
              </div>
              <?php else: ?>
              <span class="text-slate-400 text-xs"><?= $achievement['date'] ?></span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Feedbacks -->
      <div>
        <h2 class="text-slate-900 text-base font-bold mb-4">بازخوردهای اخیر</h2>
        <div class="space-y-3">
          <?php foreach ($feedbacks as $feedback): ?>
          <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all">
            <div class="flex items-start gap-3 mb-3">
              <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user text-slate-600"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="text-slate-900 text-sm font-semibold"><?= $feedback['from'] ?></h4>
                    <p class="text-slate-500 text-xs"><?= $feedback['position'] ?></p>
                  </div>
                  <div class="flex items-center gap-0.5">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <i class="fa-solid fa-star text-xs <?= $i < $feedback['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                    <?php endfor; ?>
                  </div>
                </div>
              </div>
            </div>
            <p class="text-slate-700 text-sm leading-relaxed">"<?= $feedback['message'] ?>"</p>
            <p class="text-slate-400 text-xs mt-2"><?= $feedback['date'] ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- Bottom Navigation -->
    <?php include PROJECT_ROOT . '/appga/_components/bottom-nav.php'; ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>

</body>
</html>
