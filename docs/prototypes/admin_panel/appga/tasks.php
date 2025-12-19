<?php
/**
 * صفحه لیست وظایف
 * نمایش همه وظایف کاربر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'وظایف من';
$currentTab = 'home';

// فیلتر فعال
$activeFilter = $_GET['status'] ?? 'all';

// وظایف
$allTasks = [
    [
        'id'          => 1,
        'title'       => 'بررسی درخواست مرخصی احمد رضایی',
        'priority'    => 'high',
        'dueDate'     => 'امروز - ۱۴:۰۰',
        'type'        => 'approval',
        'status'      => 'pending',
        'description' => 'درخواست مرخصی ساعتی برای روز سه‌شنبه',
    ],
    [
        'id'          => 2,
        'title'       => 'تکمیل گزارش هفتگی پروژه آلفا',
        'priority'    => 'high',
        'dueDate'     => 'امروز - ۱۷:۰۰',
        'type'        => 'task',
        'status'      => 'pending',
        'description' => 'گزارش پیشرفت هفتگی باید ارسال شود',
    ],
    [
        'id'          => 3,
        'title'       => 'شرکت در جلسه هماهنگی تیم فنی',
        'priority'    => 'medium',
        'dueDate'     => 'فردا - ۱۰:۰۰',
        'type'        => 'meeting',
        'status'      => 'pending',
        'description' => 'جلسه هفتگی تیم فنی در اتاق کنفرانس',
    ],
    [
        'id'          => 4,
        'title'       => 'بررسی و تایید سند فنی پروژه',
        'priority'    => 'medium',
        'dueDate'     => 'فردا - ۱۵:۰۰',
        'type'        => 'approval',
        'status'      => 'pending',
        'description' => 'سند معماری نرم‌افزار نیاز به بررسی دارد',
    ],
    [
        'id'          => 5,
        'title'       => 'ارسال فاکتور پروژه بتا',
        'priority'    => 'low',
        'dueDate'     => '۳ روز دیگر',
        'type'        => 'task',
        'status'      => 'pending',
        'description' => 'فاکتور مرحله دوم پروژه',
    ],
    [
        'id'          => 6,
        'title'       => 'بررسی درخواست مرخصی علی محمدی',
        'priority'    => 'high',
        'dueDate'     => 'دیروز',
        'type'        => 'approval',
        'status'      => 'completed',
        'description' => 'تایید شده',
    ],
    [
        'id'          => 7,
        'title'       => 'ارسال مستندات API',
        'priority'    => 'medium',
        'dueDate'     => '۲ روز پیش',
        'type'        => 'task',
        'status'      => 'completed',
        'description' => 'مستندات به تیم فرانت ارسال شد',
    ],
];

// فیلتر کردن
if ($activeFilter === 'pending') {
    $tasks = array_filter($allTasks, fn ($t) => $t['status'] === 'pending');
} elseif ($activeFilter === 'completed') {
    $tasks = array_filter($allTasks, fn ($t) => $t['status'] === 'completed');
} else {
    $tasks = $allTasks;
}

// شمارش
$counts = [
    'all'       => count($allTasks),
    'pending'   => count(array_filter($allTasks, fn ($t) => $t['status'] === 'pending')),
    'completed' => count(array_filter($allTasks, fn ($t) => $t['status'] === 'completed')),
];

// آیکون‌های نوع وظیفه
$typeIcons = [
    'approval' => 'fa-stamp',
    'meeting'  => 'fa-users',
    'task'     => 'fa-file-lines',
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-white">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-24">

    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white shadow-sm px-5 pt-6 pb-4">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <a href="index.php" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-arrow-right text-slate-600"></i>
          </a>
          <h1 class="text-slate-900 text-xl font-bold">وظایف من</h1>
        </div>
        <span class="bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium">
          <?= toPersianNum($counts['pending']) ?> در انتظار
        </span>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-2">
        <a href="?status=all" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all <?= $activeFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          همه (<?= toPersianNum($counts['all']) ?>)
        </a>
        <a href="?status=pending" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all <?= $activeFilter === 'pending' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          در انتظار (<?= toPersianNum($counts['pending']) ?>)
        </a>
        <a href="?status=completed" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-center transition-all <?= $activeFilter === 'completed' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          انجام شده (<?= toPersianNum($counts['completed']) ?>)
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6">

      <?php if (empty($tasks)): ?>
      <!-- Empty State -->
      <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
          <i class="fa-solid fa-check-circle text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-slate-900 text-base font-semibold mb-2">وظیفه‌ای یافت نشد</h3>
        <p class="text-slate-500 text-sm text-center">هیچ وظیفه‌ای در این دسته‌بندی وجود ندارد</p>
      </div>
      <?php else: ?>
      
      <!-- Tasks List -->
      <div class="space-y-3">
        <?php foreach ($tasks as $task): ?>
        <a href="task-detail.php?id=<?= $task['id'] ?>" class="block bg-white <?= $task['priority'] === 'high' && $task['status'] === 'pending' ? 'border-r-4 border-r-red-500' : '' ?> rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] <?= $task['status'] === 'completed' ? 'opacity-60' : '' ?>">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $task['status'] === 'completed' ? 'bg-green-100' : ($task['priority'] === 'high' ? 'bg-red-50' : 'bg-slate-100') ?>">
              <?php if ($task['status'] === 'completed'): ?>
              <i class="fa-solid fa-check text-green-600"></i>
              <?php else: ?>
              <i class="fa-solid <?= $typeIcons[$task['type']] ?> <?= $task['priority'] === 'high' ? 'text-red-600' : 'text-slate-600' ?>"></i>
              <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2 mb-1">
                <h3 class="text-slate-900 text-sm font-semibold leading-tight <?= $task['status'] === 'completed' ? 'line-through' : '' ?>"><?= $task['title'] ?></h3>
                <?php if ($task['priority'] === 'high' && $task['status'] === 'pending'): ?>
                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-lg text-xs font-medium flex-shrink-0">فوری</span>
                <?php endif; ?>
              </div>
              <p class="text-slate-500 text-xs mb-2 line-clamp-1"><?= $task['description'] ?></p>
              <div class="flex items-center gap-2">
                <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                <span class="text-slate-500 text-xs"><?= $task['dueDate'] ?></span>
                <?php if ($task['status'] === 'completed'): ?>
                <span class="text-green-600 text-xs font-medium mr-2">✓ انجام شده</span>
                <?php endif; ?>
              </div>
            </div>
            <i class="fa-solid fa-chevron-left text-slate-400 text-xs mt-3"></i>
          </div>
        </a>
        <?php endforeach; ?>
      </div>

      <?php endif; ?>

    </div>

    <!-- Bottom Navigation -->
    <?php include PROJECT_ROOT . '/appga/_components/bottom-nav.php'; ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>

</body>
</html>
