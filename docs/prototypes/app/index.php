<?php
/**
 * صفحه اصلی اپلیکیشن (Home/Dashboard)
 * نمایش آمار، اخبار، پایگاه تجربه و وظایف
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'خانه';
$currentTab = 'home';

// داده‌های آماری کاربر
$userStats = [
    'points' => '۱,۲۸۵',
    'level' => 'طلایی',
    'levelIcon' => 'fa-solid fa-trophy',
    'daysWithUs' => '۱۴۲',
];

// اخبار و اطلاعیه‌های اخیر
$news = [
    [
        'id' => 1,
        'title' => 'به‌روزرسانی سیستم گیمیفیکیشن',
        'excerpt' => 'امتیازات جدید برای فعالیت‌های آموزشی اضافه شد',
        'date' => 'امروز',
        'isPinned' => true,
        'isRead' => false,
        'badge' => 'news'
    ],
    [
        'id' => 2,
        'title' => 'رویداد تیم‌سازی فصل پاییز',
        'excerpt' => 'دعوت به شرکت در رویداد تیم‌سازی روز جمعه',
        'date' => 'دیروز',
        'isPinned' => true,
        'isRead' => false,
        'badge' => 'event'
    ],
    [
        'id' => 3,
        'title' => 'راهنمای استفاده از سیستم جدید',
        'excerpt' => 'ویدیوهای آموزشی برای کار با پلتفرم منتشر شد',
        'date' => '۲ روز پیش',
        'isPinned' => false,
        'isRead' => true,
        'badge' => 'guide'
    ],
];

// تجربیات جدید
$experiences = [
    [
        'id' => 1,
        'title' => 'بهینه‌سازی فرآیند قراردادها',
        'author' => 'احمد باقری',
        'department' => 'مالی',
        'tags' => ['قرارداد', 'بهینه‌سازی'],
        'date' => 'امروز',
        'isNew' => true
    ],
    [
        'id' => 2,
        'title' => 'کاهش زمان تولید محصول',
        'author' => 'رضا صانعی',
        'department' => 'تولید',
        'tags' => ['تولید', 'بهبود'],
        'date' => 'دیروز',
        'isNew' => true
    ],
];

// وظایف در حال انتظار
$tasks = [
    [
        'id' => 1,
        'title' => 'بررسی درخواست مرخصی احمد رضایی',
        'priority' => 'high',
        'dueDate' => 'امروز',
        'type' => 'approval'
    ],
    [
        'id' => 2,
        'title' => 'تکمیل گزارش هفتگی پروژه',
        'priority' => 'medium',
        'dueDate' => 'فردا',
        'type' => 'task'
    ],
    [
        'id' => 3,
        'title' => 'بازخورد به تجربه جدید تیم فروش',
        'priority' => 'low',
        'dueDate' => '۳ روز دیگر',
        'type' => 'feedback'
    ],
];

// شمارش موارد خوانده نشده
$unreadNewsCount = count(array_filter($news, fn($n) => !$n['isRead']));
$newExperiencesCount = count(array_filter($experiences, fn($e) => $e['isNew']));
$pendingTasksCount = count($tasks);

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative pb-20">

    <!-- Header -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-700 px-5 pt-8 pb-8 rounded-b-[32px] shadow-lg">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-user text-white text-lg"></i>
          </div>
          <div>
            <h1 class="text-white text-lg font-bold">سلام، محمدرضا</h1>
            <p class="text-white/70 text-xs">خوش آمدید 👋</p>
          </div>
        </div>
        <button class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm hover:bg-white/20 transition-all">
          <i class="fa-solid fa-bell text-white text-lg"></i>
          <?php if ($unreadNewsCount > 0): ?>
          <span class="absolute w-2 h-2 bg-red-500 rounded-full top-11 right-5 animate-pulse"></span>
          <?php endif; ?>
        </button>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 gap-3">
        <!-- امتیازات و سطح -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
          <div class="flex items-center gap-2 mb-2">
            <i class="<?= $userStats['levelIcon'] ?> text-yellow-400 text-lg"></i>
            <span class="text-white/70 text-xs">امتیاز شما</span>
          </div>
          <p class="text-white text-2xl font-bold mb-1"><?= $userStats['points'] ?></p>
          <span class="inline-flex items-center gap-1 bg-yellow-400/20 text-yellow-300 px-2 py-0.5 rounded-lg text-xs font-medium">
            <i class="fa-solid fa-star text-[8px]"></i>
            <?= $userStats['level'] ?>
          </span>
        </div>

        <!-- روزهای همراهی -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-heart text-pink-400 text-lg"></i>
            <span class="text-white/70 text-xs">روزهای همراهی</span>
          </div>
          <p class="text-white text-2xl font-bold mb-1"><?= $userStats['daysWithUs'] ?></p>
          <span class="text-white/50 text-xs">روز با ما</span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-8 space-y-8">

      <!-- اخبار و اطلاعیه‌ها -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            اخبار و اطلاعیه‌ها
            <?php if ($unreadNewsCount > 0): ?>
            <span class="text-slate-500 text-sm font-normal mr-2">(<?= $unreadNewsCount ?> خبر جدید)</span>
            <?php endif; ?>
          </h2>
          <a href="news.php" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
            همه
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
          </a>
        </div>
        <div class="space-y-3">
          <?php foreach (array_slice($news, 0, 2) as $newsItem): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all cursor-pointer <?= !$newsItem['isRead'] ? 'border-r-2 border-r-slate-900' : '' ?>">
            <div class="flex items-start justify-between gap-2 mb-2">
              <h3 class="text-slate-900 text-sm font-semibold leading-tight flex-1"><?= $newsItem['title'] ?></h3>
              <?php if ($newsItem['isPinned']): ?>
              <i class="fa-solid fa-thumbtack text-slate-400 text-xs flex-shrink-0"></i>
              <?php endif; ?>
            </div>
            <p class="text-slate-600 text-xs leading-relaxed mb-2"><?= $newsItem['excerpt'] ?></p>
            <span class="text-slate-400 text-xs"><?= $newsItem['date'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- پایگاه تجربه سازمانی -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            پایگاه تجربه
            <?php if ($newExperiencesCount > 0): ?>
            <span class="text-slate-500 text-sm font-normal mr-2">(<?= $newExperiencesCount ?> تجربه جدید)</span>
            <?php endif; ?>
          </h2>
          <a href="experiences.php" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
            همه
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
          </a>
        </div>
        <div class="space-y-3">
          <?php foreach ($experiences as $exp): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all cursor-pointer">
            <h3 class="text-slate-900 text-sm font-semibold mb-2 leading-tight"><?= $exp['title'] ?></h3>
            <div class="flex items-center gap-2 mb-3 text-xs text-slate-500">
              <span><?= $exp['author'] ?></span>
              <span>•</span>
              <span><?= $exp['department'] ?></span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1.5">
                <?php foreach ($exp['tags'] as $tag): ?>
                <span class="bg-gray-100 text-slate-600 px-2 py-0.5 rounded text-xs">
                  #<?= $tag ?>
                </span>
                <?php endforeach; ?>
              </div>
              <span class="text-slate-400 text-xs"><?= $exp['date'] ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- مدیریت وظایف -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            وظایف من
            <?php if ($pendingTasksCount > 0): ?>
            <span class="text-slate-500 text-sm font-normal mr-2">(<?= $pendingTasksCount ?> کار منتظر)</span>
            <?php endif; ?>
          </h2>
          <a href="tasks.php" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
            همه
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
          </a>
        </div>
        <div class="space-y-3">
          <?php foreach (array_slice($tasks, 0, 3) as $task): ?>
          <div class="bg-white border <?= $task['priority'] === 'high' ? 'border-r-2 border-r-slate-900' : 'border-gray-200' ?> rounded-xl p-4 hover:border-gray-300 transition-all cursor-pointer">
            <div class="flex items-start justify-between gap-3 mb-2">
              <h3 class="text-slate-900 text-sm font-semibold leading-tight flex-1"><?= $task['title'] ?></h3>
              <?php if ($task['priority'] === 'high'): ?>
              <span class="bg-slate-900 text-white px-2 py-0.5 rounded text-xs font-medium flex-shrink-0">
                فوری
              </span>
              <?php endif; ?>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500 text-xs"><?= $task['dueDate'] ?></span>
              <button class="text-slate-600 text-xs font-medium hover:text-slate-900">
                مشاهده جزئیات
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- اخبار پین شده -->
      <?php
      $pinnedNews = array_filter($news, fn($n) => $n['isPinned']);
      if (count($pinnedNews) > 2):
      ?>
      <div>
        <div class="flex items-center gap-2 mb-4">
          <h2 class="text-slate-900 text-base font-bold">اخبار پین شده</h2>
        </div>
        <div class="space-y-3">
          <?php foreach ($pinnedNews as $newsItem): ?>
          <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all cursor-pointer">
            <div class="flex items-start justify-between gap-2 mb-1">
              <h3 class="text-slate-900 text-sm font-semibold leading-tight flex-1"><?= $newsItem['title'] ?></h3>
              <i class="fa-solid fa-thumbtack text-slate-400 text-xs flex-shrink-0"></i>
            </div>
            <p class="text-slate-600 text-xs leading-relaxed mb-2"><?= $newsItem['excerpt'] ?></p>
            <span class="text-slate-400 text-xs"><?= $newsItem['date'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>

    <!-- Bottom Navigation -->
    <?php component('app-bottom-nav', ['currentTab' => $currentTab]); ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script>
    // Auto-refresh notification badge
    document.addEventListener('DOMContentLoaded', function() {
      // Smooth scroll
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });
    });
  </script>

</body>
</html>
