<?php
/**
 * صفحه تاریخچه - هیستوری
 * نمایش آخرین اطلاعیه‌ها، کارها، اخبار و فایل‌ها
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'تاریخچه';
$currentTab = 'history';

// تب فعال
$activeFilter = $_GET['filter'] ?? 'all';

// آیتم‌های تاریخچه
$historyItems = [
    [
        'id'          => 1,
        'type'        => 'notification',
        'title'       => 'جلسه هفتگی تیم فردا ساعت ۱۰ صبح',
        'description' => 'حضور همه اعضای تیم الزامی است',
        'date'        => 'امروز',
        'time'        => '۱۵:۳۰',
        'icon'        => 'fa-bell',
        'color'       => 'purple',
        'isRead'      => false,
    ],
    [
        'id'          => 2,
        'type'        => 'task',
        'title'       => 'تکمیل گزارش هفتگی پروژه',
        'description' => 'گزارش باید تا پایان امروز ارسال شود',
        'date'        => 'امروز',
        'time'        => '۱۴:۰۰',
        'icon'        => 'fa-list-check',
        'color'       => 'blue',
        'isRead'      => true,
    ],
    [
        'id'          => 3,
        'type'        => 'news',
        'title'       => 'تغییر در سیاست‌های مرخصی سالانه',
        'description' => 'سیاست‌های جدید از ابتدای ماه آینده اجرا می‌شود',
        'date'        => 'امروز',
        'time'        => '۱۱:۲۰',
        'icon'        => 'fa-newspaper',
        'color'       => 'amber',
        'isRead'      => false,
    ],
    [
        'id'          => 4,
        'type'        => 'file',
        'title'       => 'قرارداد همکاری فصل زمستان',
        'description' => 'فایل PDF - ۲.۵ مگابایت',
        'date'        => 'دیروز',
        'time'        => '۱۶:۴۵',
        'icon'        => 'fa-file-pdf',
        'color'       => 'red',
        'isRead'      => true,
        'fileType'    => 'PDF',
        'fileSize'    => '۲.۵ مگابایت',
        'uploadedBy'  => 'واحد منابع انسانی',
    ],
    [
        'id'          => 5,
        'type'        => 'task',
        'title'       => 'بررسی درخواست مرخصی علی محمدی',
        'description' => 'تایید شده توسط شما',
        'date'        => 'دیروز',
        'time'        => '۱۰:۳۰',
        'icon'        => 'fa-check-circle',
        'color'       => 'green',
        'isRead'      => true,
    ],
    [
        'id'          => 6,
        'type'        => 'notification',
        'title'       => 'یادآوری: ارزیابی عملکرد فصلی',
        'description' => 'لطفاً فرم ارزیابی را تا پایان هفته تکمیل کنید',
        'date'        => 'دیروز',
        'time'        => '۰۹:۰۰',
        'icon'        => 'fa-exclamation-circle',
        'color'       => 'orange',
        'isRead'      => false,
    ],
    [
        'id'          => 7,
        'type'        => 'file',
        'title'       => 'گزارش عملکرد ماهانه آذر',
        'description' => 'فایل Excel - ۱.۲ مگابایت',
        'date'        => '۲ روز پیش',
        'time'        => '۱۴:۲۰',
        'icon'        => 'fa-file-excel',
        'color'       => 'green',
        'isRead'      => true,
        'fileType'    => 'Excel',
        'fileSize'    => '۱.۲ مگابایت',
        'uploadedBy'  => 'واحد مالی',
    ],
    [
        'id'          => 8,
        'type'        => 'news',
        'title'       => 'معرفی همکار جدید در تیم توسعه',
        'description' => 'خانم زهرا کریمی به تیم توسعه پیوست',
        'date'        => '۲ روز پیش',
        'time'        => '۱۰:۰۰',
        'icon'        => 'fa-user-plus',
        'color'       => 'sky',
        'isRead'      => true,
    ],
    [
        'id'          => 9,
        'type'        => 'task',
        'title'       => 'ارسال مستندات پروژه بتا',
        'description' => 'مستندات با موفقیت ارسال شد',
        'date'        => '۳ روز پیش',
        'time'        => '۱۱:۴۵',
        'icon'        => 'fa-paper-plane',
        'color'       => 'indigo',
        'isRead'      => true,
    ],
    [
        'id'          => 10,
        'type'        => 'notification',
        'title'       => 'به‌روزرسانی سیستم امشب ساعت ۲۳',
        'description' => 'سیستم برای ۲ ساعت در دسترس نخواهد بود',
        'date'        => '۳ روز پیش',
        'time'        => '۰۸:۳۰',
        'icon'        => 'fa-wrench',
        'color'       => 'slate',
        'isRead'      => true,
    ],
];

// فیلتر کردن بر اساس نوع
if ($activeFilter !== 'all') {
    $historyItems = array_filter($historyItems, fn ($item) => $item['type'] === $activeFilter);
}

// گروه‌بندی بر اساس تاریخ
$groupedHistory = [];

foreach ($historyItems as $item) {
    $groupedHistory[$item['date']][] = $item;
}

// تعداد هر نوع
$counts = [
    'all'          => count($historyItems),
    'notification' => count(array_filter($historyItems, fn ($i) => $i['type'] === 'notification')),
    'task'         => count(array_filter($historyItems, fn ($i) => $i['type'] === 'task')),
    'news'         => count(array_filter($historyItems, fn ($i) => $i['type'] === 'news')),
    'file'         => count(array_filter($historyItems, fn ($i) => $i['type'] === 'file')),
];

// رنگ‌های آیکون‌ها
$colorClasses = [
    'purple' => 'bg-purple-50 text-purple-600',
    'blue'   => 'bg-blue-50 text-blue-600',
    'amber'  => 'bg-amber-50 text-amber-600',
    'red'    => 'bg-red-50 text-red-600',
    'green'  => 'bg-green-50 text-green-600',
    'orange' => 'bg-orange-50 text-orange-600',
    'sky'    => 'bg-sky-50 text-sky-600',
    'indigo' => 'bg-indigo-50 text-indigo-600',
    'slate'  => 'bg-slate-100 text-slate-600',
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
        <h1 class="text-slate-900 text-xl font-bold flex items-center gap-2">
          <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
          تاریخچه
        </h1>
        <button class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
          <i class="fa-solid fa-filter text-slate-600"></i>
        </button>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-5 px-5 scrollbar-hide">
        <a href="?filter=all" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          همه
        </a>
        <a href="?filter=notification" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'notification' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-bell ml-1.5"></i>
          اطلاعیه
        </a>
        <a href="?filter=task" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'task' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-list-check ml-1.5"></i>
          کارها
        </a>
        <a href="?filter=news" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'news' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-newspaper ml-1.5"></i>
          اخبار
        </a>
        <a href="?filter=file" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-medium transition-all <?= $activeFilter === 'file' ? 'bg-slate-900 text-white' : 'bg-gray-100 text-slate-600 hover:bg-gray-200' ?>">
          <i class="fa-solid fa-folder ml-1.5"></i>
          فایل‌ها
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6">

      <?php if (empty($historyItems)): ?>
      <!-- Empty State -->
      <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
          <i class="fa-solid fa-inbox text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-slate-900 text-base font-semibold mb-2">موردی یافت نشد</h3>
        <p class="text-slate-500 text-sm text-center">هیچ موردی در این دسته‌بندی وجود ندارد</p>
      </div>
      <?php else: ?>
      
      <!-- History Items by Date -->
      <div class="space-y-6">
        <?php foreach ($groupedHistory as $date => $items): ?>
        <div>
          <!-- Date Header -->
          <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-calendar text-white text-xs"></i>
            </div>
            <span class="text-slate-900 text-sm font-bold"><?= $date ?></span>
          </div>

          <!-- Items -->
          <div class="space-y-3">
            <?php foreach ($items as $item): ?>
            <!-- Item Card -->
            <?php if ($item['type'] === 'task'): ?>
            <a href="task-detail.php?id=<?= $item['id'] ?>" class="block bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] <?= !$item['isRead'] ? 'border-r-4 border-r-slate-900' : '' ?>">
            <?php else: ?>
            <div onclick="<?= $item['type'] === 'file' ? 'openFileModal(' . htmlspecialchars(json_encode($item), ENT_QUOTES) . ')' : 'openItemModal(' . htmlspecialchars(json_encode($item), ENT_QUOTES) . ')' ?>" 
                 class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] <?= !$item['isRead'] ? 'border-r-4 border-r-slate-900' : '' ?>">
            <?php endif; ?>
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $colorClasses[$item['color']] ?>">
                  <i class="fa-solid <?= $item['icon'] ?>"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2 mb-1">
                    <h3 class="text-slate-900 text-sm font-semibold leading-tight flex-1"><?= $item['title'] ?></h3>
                    <span class="text-slate-400 text-xs flex-shrink-0"><?= $item['time'] ?></span>
                  </div>
                  <p class="text-slate-500 text-xs leading-relaxed line-clamp-2"><?= $item['description'] ?></p>
                </div>
              </div>
            <?php if ($item['type'] === 'task'): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php endif; ?>

    </div>

    <!-- Bottom Navigation -->
    <?php include PROJECT_ROOT . '/appga/_components/bottom-nav.php'; ?>

  </div>

  <!-- File Modal -->
  <div id="fileModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl transform transition-all">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            <div id="fileModalIcon" class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"></div>
            <div class="flex-1 min-w-0 pt-1">
              <h2 id="fileModalTitle" class="text-slate-900 text-base font-bold leading-tight mb-1"></h2>
              <div class="flex items-center gap-2 text-xs">
                <span id="fileModalType" class="text-slate-500 font-medium"></span>
                <span class="text-slate-300">•</span>
                <span id="fileModalSize" class="text-slate-500"></span>
              </div>
            </div>
          </div>
          <button onclick="closeFileModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all flex-shrink-0">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-160px)]">
        
        <!-- Description -->
        <div class="mb-6">
          <p id="fileModalDescription" class="text-slate-700 text-sm leading-relaxed"></p>
        </div>

        <!-- Meta Info -->
        <div class="space-y-3">
          
          <!-- Date & Time -->
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-clock text-slate-600 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-slate-400 text-xs mb-0.5">زمان آپلود</p>
              <p id="fileModalDateTime" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

          <!-- Uploader -->
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-user text-slate-600 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-slate-400 text-xs mb-0.5">آپلود شده توسط</p>
              <p id="fileModalUploader" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

        </div>

      </div>

      <!-- Modal Footer -->
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <div class="grid grid-cols-2 gap-3">
          <button onclick="closeFileModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
            بستن
          </button>
          <button onclick="downloadFile()" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <i class="fa-solid fa-download"></i>
            دانلود فایل
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Item Modal (for notification, task, news) -->
  <div id="itemModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl transform transition-all">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            <div id="itemModalIcon" class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"></div>
            <div class="flex-1 min-w-0 pt-1">
              <h2 id="itemModalTitle" class="text-slate-900 text-base font-bold leading-tight mb-1"></h2>
              <span id="itemModalBadge" class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium"></span>
            </div>
          </div>
          <button onclick="closeItemModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all flex-shrink-0">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-160px)]">
        
        <!-- Description -->
        <div class="mb-6">
          <p id="itemModalDescription" class="text-slate-700 text-sm leading-relaxed"></p>
        </div>

        <!-- Meta Info -->
        <div class="space-y-3">
          
          <!-- Date & Time -->
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-clock text-slate-600 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-slate-400 text-xs mb-0.5">زمان دریافت</p>
              <p id="itemModalDateTime" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

          <!-- Status -->
          <div id="itemModalStatusContainer" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-circle-check text-slate-600 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-slate-400 text-xs mb-0.5">وضعیت</p>
              <p id="itemModalStatus" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

        </div>

      </div>

      <!-- Modal Footer -->
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <button onclick="closeItemModal()" class="w-full h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98]">
          متوجه شدم
        </button>
      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  
  <script>
    // رنگ‌های آیکون
    const colorClasses = {
      'purple': 'bg-purple-50 text-purple-600',
      'amber': 'bg-amber-50 text-amber-600',
      'orange': 'bg-orange-50 text-orange-600',
      'red': 'bg-red-50 text-red-600',
      'green': 'bg-green-50 text-green-600',
      'blue': 'bg-blue-50 text-blue-600',
      'sky': 'bg-sky-50 text-sky-600',
      'indigo': 'bg-indigo-50 text-indigo-600',
      'slate': 'bg-slate-100 text-slate-600',
    };

    let currentFile = null;

    // باز کردن مودال فایل
    function openFileModal(file) {
      currentFile = file;
      const modal = document.getElementById('fileModal');
      const modalIcon = document.getElementById('fileModalIcon');
      const modalTitle = document.getElementById('fileModalTitle');
      const modalType = document.getElementById('fileModalType');
      const modalSize = document.getElementById('fileModalSize');
      const modalDescription = document.getElementById('fileModalDescription');
      const modalDateTime = document.getElementById('fileModalDateTime');
      const modalUploader = document.getElementById('fileModalUploader');

      // تنظیم آیکون
      modalIcon.className = `w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 ${colorClasses[file.color]}`;
      modalIcon.innerHTML = `<i class="fa-solid ${file.icon}"></i>`;

      // تنظیم محتوا
      modalTitle.textContent = file.title;
      modalType.textContent = file.fileType || 'فایل';
      modalSize.textContent = file.fileSize || '—';
      modalDescription.textContent = file.description;
      modalDateTime.textContent = `${file.date} - ${file.time}`;
      modalUploader.textContent = file.uploadedBy || 'نامشخص';

      // نمایش مودال
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.classList.add('animate-fade-in');
      }, 10);
    }

    // بستن مودال
    function closeFileModal() {
      const modal = document.getElementById('fileModal');
      modal.classList.remove('animate-fade-in');
      setTimeout(() => {
        modal.classList.add('hidden');
        currentFile = null;
      }, 200);
    }

    // دانلود فایل
    function downloadFile() {
      if (currentFile) {
        alert(`در حال دانلود: ${currentFile.title}`);
        // اینجا می‌توانید لاجیک دانلود واقعی را پیاده‌سازی کنید
      }
    }

    // بستن با کلیک روی پس‌زمینه
    document.getElementById('fileModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeFileModal();
      }
    });

    // باز کردن مودال آیتم (notification, task, news)
    function openItemModal(item) {
      const modal = document.getElementById('itemModal');
      const modalIcon = document.getElementById('itemModalIcon');
      const modalTitle = document.getElementById('itemModalTitle');
      const modalBadge = document.getElementById('itemModalBadge');
      const modalDescription = document.getElementById('itemModalDescription');
      const modalDateTime = document.getElementById('itemModalDateTime');
      const modalStatus = document.getElementById('itemModalStatus');

      // تنظیم آیکون
      modalIcon.className = `w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 ${colorClasses[item.color]}`;
      modalIcon.innerHTML = `<i class="fa-solid ${item.icon}"></i>`;

      // تنظیم محتوا
      modalTitle.textContent = item.title;
      
      // تنظیم نوع
      const typeLabels = {
        'notification': 'اطلاعیه',
        'task': 'وظیفه',
        'news': 'خبر'
      };
      const typeBgColors = {
        'notification': 'bg-purple-100 text-purple-700',
        'task': 'bg-blue-100 text-blue-700',
        'news': 'bg-amber-100 text-amber-700'
      };
      modalBadge.textContent = typeLabels[item.type] || item.type;
      modalBadge.className = `inline-block px-2.5 py-1 rounded-lg text-xs font-medium ${typeBgColors[item.type]}`;
      
      modalDescription.textContent = item.description;
      modalDateTime.textContent = `${item.date} - ${item.time}`;
      modalStatus.textContent = item.isRead ? 'خوانده شده' : 'خوانده نشده';

      // نمایش مودال
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.classList.add('animate-fade-in');
      }, 10);
    }

    // بستن مودال آیتم
    function closeItemModal() {
      const modal = document.getElementById('itemModal');
      modal.classList.remove('animate-fade-in');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 200);
    }

    // بستن با کلیک روی پس‌زمینه - آیتم مودال
    document.getElementById('itemModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeItemModal();
      }
    });

    // بستن با کلید ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const fileModal = document.getElementById('fileModal');
        const itemModal = document.getElementById('itemModal');
        
        if (!fileModal.classList.contains('hidden')) {
          closeFileModal();
        } else if (!itemModal.classList.contains('hidden')) {
          closeItemModal();
        }
      }
    });
  </script>

  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .animate-fade-in {
      animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    #fileModal > div,
    #itemModal > div {
      animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
      from {
        transform: translateY(100%);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>

</body>
</html>
