<?php
/**
 * داشبورد اصلی اپلیکیشن کارکنان
 * نمایش آمار، وظایف، اسناد و اطلاعیه‌ها
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'داشبورد';
$currentTab = 'home';

// اطلاعات کاربر
$user = [
    'name' => 'محمدرضا احمدی',
    'firstName' => 'محمدرضا',
    'avatar' => 'https://picsum.photos/seed/avatar1/200/200',
    'department' => 'فناوری اطلاعات',
    'position' => 'کارشناس ارشد',
];

// آمار کاربر
$userStats = [
    'totalTasks' => '۲۴',
    'daysWithCompany' => '۳۸۵',
];

// شمارش‌ها برای باکس‌های اصلی
$counts = [
    'tasks' => '۷',
    'documents' => '۱۲',
    'notifications' => '۴',
];

// وظایف جاری
$tasks = [
    [
        'id' => 1,
        'title' => 'بررسی درخواست مرخصی احمد رضایی',
        'priority' => 'high',
        'dueDate' => 'امروز - ۱۴:۰۰',
        'type' => 'approval',
        'status' => 'pending',
    ],
    [
        'id' => 2,
        'title' => 'تکمیل گزارش هفتگی پروژه آلفا',
        'priority' => 'high',
        'dueDate' => 'امروز - ۱۷:۰۰',
        'type' => 'task',
        'status' => 'pending',
    ],
    [
        'id' => 3,
        'title' => 'شرکت در جلسه هماهنگی تیم فنی',
        'priority' => 'medium',
        'dueDate' => 'فردا - ۱۰:۰۰',
        'type' => 'meeting',
        'status' => 'pending',
    ],
    [
        'id' => 4,
        'title' => 'بررسی و تایید سند فنی پروژه',
        'priority' => 'medium',
        'dueDate' => 'فردا - ۱۵:۰۰',
        'type' => 'approval',
        'status' => 'pending',
    ],
];

// اسناد اخیر
$documents = [
    [
        'id' => 1,
        'title' => 'قرارداد همکاری فصل زمستان',
        'type' => 'pdf',
        'date' => 'امروز',
        'time' => '۱۴:۳۰',
        'icon' => 'fa-file-pdf',
        'color' => 'red',
        'description' => 'قرارداد همکاری فصل زمستان برای بررسی و امضا',
        'fileType' => 'PDF',
        'fileSize' => '۲.۵ مگابایت',
        'uploadedBy' => 'واحد منابع انسانی',
    ],
    [
        'id' => 2,
        'title' => 'گزارش عملکرد ماهانه',
        'type' => 'excel',
        'date' => 'دیروز',
        'time' => '۱۶:۱۵',
        'icon' => 'fa-file-excel',
        'color' => 'green',
        'description' => 'گزارش جامع عملکرد ماهانه واحد فروش',
        'fileType' => 'Excel',
        'fileSize' => '۱.۸ مگابایت',
        'uploadedBy' => 'واحد مالی',
    ],
];

// اطلاعیه‌های اخیر
$notifications = [
    [
        'id' => 1,
        'title' => 'جلسه هفتگی تیم فردا ساعت ۱۰',
        'type' => 'meeting',
        'isRead' => false,
        'date' => 'امروز',
    ],
    [
        'id' => 2,
        'title' => 'تغییر در سیاست‌های مرخصی',
        'type' => 'announcement',
        'isRead' => false,
        'date' => 'دیروز',
    ],
];

// وظایف فوری (۴۸ ساعت آینده)
$urgentTasks = array_filter($tasks, fn($t) => in_array($t['priority'], ['high', 'medium']));

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-white">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-24">

    <!-- Header -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-700 px-5 pt-8 pb-6 rounded-b-[32px] shadow-lg">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/20">
            <img src="<?= $user['avatar'] ?>" alt="<?= $user['name'] ?>" class="w-full h-full object-cover">
          </div>
          <div>
            <h1 class="text-white text-lg font-bold">سلام، <?= $user['firstName'] ?></h1>
            <p class="text-white/60 text-xs"><?= $user['position'] ?> • <?= $user['department'] ?></p>
          </div>
        </div>
        <a href="notifications.php" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm hover:bg-white/20 transition-all relative">
          <i class="fa-solid fa-bell text-white text-lg"></i>
          <?php if ($counts['notifications'] > 0): ?>
          <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold"><?= $counts['notifications'] ?></span>
          <?php endif; ?>
        </a>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 gap-3">
        <!-- تعداد کارها -->
        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-list-check text-emerald-400 text-lg"></i>
            <span class="text-white/70 text-xs">مجموع کارها</span>
          </div>
          <p class="text-white text-2xl font-bold"><?= $userStats['totalTasks'] ?></p>
          <span class="text-white/50 text-xs">وظیفه انجام شده</span>
        </div>

        <!-- روزهای همکاری -->
        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-heart text-pink-400 text-lg"></i>
            <span class="text-white/70 text-xs">روزهای همکاری</span>
          </div>
          <p class="text-white text-2xl font-bold"><?= $userStats['daysWithCompany'] ?></p>
          <span class="text-white/50 text-xs">روز با شرکت</span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6 space-y-6">

      <!-- Quick Access Boxes -->
      <div class="grid grid-cols-3 gap-3">
        <!-- وظایف -->
        <a href="tasks.php" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-blue-100 transition-colors relative">
              <i class="fa-solid fa-tasks text-blue-600 text-xl"></i>
              <?php if ($counts['tasks'] > 0): ?>
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold"><?= $counts['tasks'] ?></span>
              <?php endif; ?>
            </div>
            <p class="text-slate-900 text-sm font-semibold">وظایف</p>
          </div>
        </a>

        <!-- اسناد -->
        <a href="documents.php" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-amber-100 transition-colors relative">
              <i class="fa-solid fa-folder-open text-amber-600 text-xl"></i>
              <?php if ($counts['documents'] > 0): ?>
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-amber-600 rounded-full flex items-center justify-center text-white text-xs font-bold"><?= $counts['documents'] ?></span>
              <?php endif; ?>
            </div>
            <p class="text-slate-900 text-sm font-semibold">اسناد</p>
          </div>
        </a>

        <!-- اطلاعیه‌ها -->
        <a href="notifications.php" class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all active:scale-95 group">
          <div class="flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-2 group-hover:bg-purple-100 transition-colors relative">
              <i class="fa-solid fa-bell text-purple-600 text-xl"></i>
              <?php if ($counts['notifications'] > 0): ?>
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold"><?= $counts['notifications'] ?></span>
              <?php endif; ?>
            </div>
            <p class="text-slate-900 text-sm font-semibold">اطلاعیه‌ها</p>
          </div>
        </a>
      </div>

      <!-- Urgent Tasks (48 hours) -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
            <h2 class="text-slate-900 text-base font-bold">کارهای فوری</h2>
            <span class="text-slate-500 text-xs">(۴۸ ساعت آینده)</span>
          </div>
          <a href="tasks.php" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
            همه
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
          </a>
        </div>
        <div class="space-y-3">
          <?php foreach ($urgentTasks as $task): ?>
          <a href="task-detail.php?id=<?= $task['id'] ?>" class="block bg-white <?= $task['priority'] === 'high' ? 'border-r-4 border-r-red-500' : '' ?> rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98]">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $task['priority'] === 'high' ? 'bg-red-50' : 'bg-slate-100' ?>">
                <?php if ($task['type'] === 'approval'): ?>
                <i class="fa-solid fa-stamp <?= $task['priority'] === 'high' ? 'text-red-600' : 'text-slate-600' ?>"></i>
                <?php elseif ($task['type'] === 'meeting'): ?>
                <i class="fa-solid fa-users <?= $task['priority'] === 'high' ? 'text-red-600' : 'text-slate-600' ?>"></i>
                <?php else: ?>
                <i class="fa-solid fa-file-lines <?= $task['priority'] === 'high' ? 'text-red-600' : 'text-slate-600' ?>"></i>
                <?php endif; ?>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-slate-900 text-sm font-semibold leading-tight mb-1 line-clamp-2"><?= $task['title'] ?></h3>
                <div class="flex items-center gap-2">
                  <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                  <span class="text-slate-500 text-xs"><?= $task['dueDate'] ?></span>
                </div>
              </div>
              <?php if ($task['priority'] === 'high'): ?>
              <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-lg text-xs font-medium flex-shrink-0">فوری</span>
              <?php endif; ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-2 gap-3">
        <a href="tasks.php?status=pending" class="flex items-center gap-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-4 text-white hover:shadow-lg transition-all active:scale-95">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-clock text-lg"></i>
          </div>
          <div>
            <p class="text-white/80 text-xs">در انتظار</p>
            <p class="text-white text-lg font-bold"><?= toPersianNum(count($urgentTasks)) ?> کار</p>
          </div>
        </a>
        <a href="reports.php" class="flex items-center gap-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-4 text-white hover:shadow-lg transition-all active:scale-95">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-chart-pie text-lg"></i>
          </div>
          <div>
            <p class="text-white/80 text-xs">گزارش عملکرد</p>
            <p class="text-white text-lg font-bold">مشاهده</p>
          </div>
        </a>
      </div>

      <!-- Recent Documents -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">اسناد اخیر</h2>
          <a href="documents.php" class="text-slate-600 text-xs font-medium flex items-center gap-1 hover:gap-2 transition-all">
            همه
            <i class="fa-solid fa-chevron-left text-[10px]"></i>
          </a>
        </div>
        <div class="space-y-2">
          <?php foreach ($documents as $doc): ?>
          <div onclick="openFileModal(<?= htmlspecialchars(json_encode($doc), ENT_QUOTES) ?>)" class="flex items-center gap-3 bg-white rounded-2xl p-3 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98]">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $doc['type'] === 'pdf' ? 'bg-red-50' : 'bg-green-50' ?>">
              <?php if ($doc['type'] === 'pdf'): ?>
              <i class="fa-solid fa-file-pdf text-red-600"></i>
              <?php else: ?>
              <i class="fa-solid fa-file-excel text-green-600"></i>
              <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-slate-900 text-sm font-medium truncate"><?= $doc['title'] ?></h3>
              <p class="text-slate-500 text-xs"><?= $doc['date'] ?></p>
            </div>
            <i class="fa-solid fa-chevron-left text-slate-400 text-xs"></i>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

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

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  
  <script>
    // رنگ‌های آیکون
    const colorClasses = {
      'red': 'bg-red-50 text-red-600',
      'green': 'bg-green-50 text-green-600',
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

    // بستن با کلید ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const fileModal = document.getElementById('fileModal');
        if (!fileModal.classList.contains('hidden')) {
          closeFileModal();
        }
      }
    });
  </script>

  <style>
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

    #fileModal > div {
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
