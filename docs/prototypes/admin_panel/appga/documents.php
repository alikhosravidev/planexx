<?php
/**
 * صفحه اسناد و فایل‌ها
 * نمایش فایل‌های مرتبط با کاربر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'اسناد من';
$currentTab = 'home';

// فیلتر فعال
$activeFilter = $_GET['type'] ?? 'all';

// اسناد
$allDocuments = [
    [
        'id'       => 1,
        'title'    => 'قرارداد همکاری فصل زمستان',
        'type'     => 'pdf',
        'category' => 'contract',
        'size'     => '۲.۵ مگابایت',
        'date'     => 'امروز',
        'isNew'    => true,
    ],
    [
        'id'       => 2,
        'title'    => 'گزارش عملکرد ماهانه آذر',
        'type'     => 'excel',
        'category' => 'report',
        'size'     => '۱.۲ مگابایت',
        'date'     => 'دیروز',
        'isNew'    => true,
    ],
    [
        'id'       => 3,
        'title'    => 'فیش حقوقی آذرماه ۱۴۰۳',
        'type'     => 'pdf',
        'category' => 'payroll',
        'size'     => '۳۵۰ کیلوبایت',
        'date'     => '۲ روز پیش',
        'isNew'    => false,
    ],
    [
        'id'       => 4,
        'title'    => 'مستندات فنی پروژه آلفا',
        'type'     => 'word',
        'category' => 'document',
        'size'     => '۵.۸ مگابایت',
        'date'     => '۳ روز پیش',
        'isNew'    => false,
    ],
    [
        'id'       => 5,
        'title'    => 'فیش حقوقی آبان‌ماه ۱۴۰۳',
        'type'     => 'pdf',
        'category' => 'payroll',
        'size'     => '۳۴۸ کیلوبایت',
        'date'     => 'هفته پیش',
        'isNew'    => false,
    ],
    [
        'id'       => 6,
        'title'    => 'گزارش عملکرد ماهانه آبان',
        'type'     => 'excel',
        'category' => 'report',
        'size'     => '۱.۱ مگابایت',
        'date'     => 'هفته پیش',
        'isNew'    => false,
    ],
    [
        'id'       => 7,
        'title'    => 'دستورالعمل امنیت اطلاعات',
        'type'     => 'pdf',
        'category' => 'document',
        'size'     => '۸۵۰ کیلوبایت',
        'date'     => '۲ هفته پیش',
        'isNew'    => false,
    ],
    [
        'id'       => 8,
        'title'    => 'پرزنتیشن جلسه معرفی محصول',
        'type'     => 'ppt',
        'category' => 'presentation',
        'size'     => '۱۲ مگابایت',
        'date'     => '۲ هفته پیش',
        'isNew'    => false,
    ],
];

// فیلتر کردن
if ($activeFilter !== 'all') {
    $documents = array_filter($allDocuments, fn ($d) => $d['category'] === $activeFilter);
} else {
    $documents = $allDocuments;
}

// شمارش
$counts = [
    'all'      => count($allDocuments),
    'contract' => count(array_filter($allDocuments, fn ($d) => $d['category'] === 'contract')),
    'report'   => count(array_filter($allDocuments, fn ($d) => $d['category'] === 'report')),
    'payroll'  => count(array_filter($allDocuments, fn ($d) => $d['category'] === 'payroll')),
    'document' => count(array_filter($allDocuments, fn ($d) => $d['category'] === 'document')),
];

// آیکون‌های نوع فایل
$typeIcons = [
    'pdf'   => ['icon' => 'fa-file-pdf', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
    'excel' => ['icon' => 'fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
    'word'  => ['icon' => 'fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
    'ppt'   => ['icon' => 'fa-file-powerpoint', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50'],
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
          <h1 class="text-slate-900 text-xl font-bold">اسناد من</h1>
        </div>
        <button class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
          <i class="fa-solid fa-search text-slate-600"></i>
        </button>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-5 px-5 scrollbar-hide">
        <a href="?type=all" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          همه (<?= toPersianNum($counts['all']) ?>)
        </a>
        <a href="?type=payroll" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'payroll' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-money-bill ml-1.5"></i>
          فیش حقوقی
        </a>
        <a href="?type=contract" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'contract' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-file-contract ml-1.5"></i>
          قراردادها
        </a>
        <a href="?type=report" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'report' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-chart-bar ml-1.5"></i>
          گزارشات
        </a>
        <a href="?type=document" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'document' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-file-lines ml-1.5"></i>
          مستندات
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6">

      <?php if (empty($documents)): ?>
      <!-- Empty State -->
      <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
          <i class="fa-solid fa-folder-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-slate-900 text-base font-semibold mb-2">فایلی یافت نشد</h3>
        <p class="text-slate-500 text-sm text-center">هیچ فایلی در این دسته‌بندی وجود ندارد</p>
      </div>
      <?php else: ?>
      
      <!-- Documents List -->
      <div class="space-y-3">
        <?php foreach ($documents as $doc): ?>
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] <?= $doc['isNew'] ? 'border-r-4 border-r-blue-500' : '' ?>">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $typeIcons[$doc['type']]['bg'] ?>">
              <i class="fa-solid <?= $typeIcons[$doc['type']]['icon'] ?> text-xl <?= $typeIcons[$doc['type']]['color'] ?>"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <h3 class="text-slate-900 text-sm font-semibold truncate"><?= $doc['title'] ?></h3>
                <?php if ($doc['isNew']): ?>
                <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-medium flex-shrink-0">جدید</span>
                <?php endif; ?>
              </div>
              <div class="flex items-center gap-3 text-slate-500 text-xs">
                <span><?= $doc['size'] ?></span>
                <span>•</span>
                <span><?= $doc['date'] ?></span>
              </div>
            </div>
            <button class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-all">
              <i class="fa-solid fa-download text-slate-600 text-sm"></i>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php endif; ?>

    </div>

    <!-- Bottom Navigation -->
    <?php include PROJECT_ROOT . '/appga/_components/bottom-nav.php'; ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>

  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>

</body>
</html>
