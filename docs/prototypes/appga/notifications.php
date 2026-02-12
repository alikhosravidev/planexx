<?php
/**
 * صفحه اطلاعیه‌ها
 * نمایش همه اطلاعیه‌های کاربر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'اطلاعیه‌ها';
$currentTab = 'home';

// اطلاعیه‌ها
$notifications = [
    [
        'id' => 1,
        'title' => 'جلسه هفتگی تیم فردا ساعت ۱۰ صبح',
        'description' => 'حضور همه اعضای تیم فنی الزامی است. موضوع: بررسی پیشرفت پروژه آلفا',
        'type' => 'meeting',
        'icon' => 'fa-users',
        'color' => 'purple',
        'isRead' => false,
        'date' => 'امروز',
        'time' => '۱۵:۳۰',
    ],
    [
        'id' => 2,
        'title' => 'تغییر در سیاست‌های مرخصی سالانه',
        'description' => 'سیاست‌های جدید از ابتدای ماه آینده اجرایی می‌شود. برای اطلاعات بیشتر به پورتال منابع انسانی مراجعه کنید.',
        'type' => 'announcement',
        'icon' => 'fa-bullhorn',
        'color' => 'amber',
        'isRead' => false,
        'date' => 'امروز',
        'time' => '۱۱:۲۰',
    ],
    [
        'id' => 3,
        'title' => 'یادآوری: ارزیابی عملکرد فصلی',
        'description' => 'لطفاً فرم ارزیابی عملکرد فصل پاییز را تا پایان هفته جاری تکمیل کنید.',
        'type' => 'reminder',
        'icon' => 'fa-bell',
        'color' => 'orange',
        'isRead' => false,
        'date' => 'دیروز',
        'time' => '۰۹:۰۰',
    ],
    [
        'id' => 4,
        'title' => 'وظیفه جدید به شما اختصاص یافت',
        'description' => 'بررسی درخواست مرخصی احمد رضایی - مهلت: امروز ساعت ۱۴:۰۰',
        'type' => 'task',
        'icon' => 'fa-list-check',
        'color' => 'blue',
        'isRead' => false,
        'date' => 'دیروز',
        'time' => '۰۸:۳۰',
    ],
    [
        'id' => 5,
        'title' => 'به‌روزرسانی سیستم انجام شد',
        'description' => 'نسخه جدید سیستم با بهبودهای عملکردی منتشر شد.',
        'type' => 'system',
        'icon' => 'fa-gear',
        'color' => 'slate',
        'isRead' => true,
        'date' => '۲ روز پیش',
        'time' => '۲۳:۰۰',
    ],
    [
        'id' => 6,
        'title' => 'معرفی همکار جدید',
        'description' => 'خانم زهرا کریمی به عنوان کارشناس توسعه به تیم ما پیوست.',
        'type' => 'announcement',
        'icon' => 'fa-user-plus',
        'color' => 'green',
        'isRead' => true,
        'date' => '۳ روز پیش',
        'time' => '۱۰:۰۰',
    ],
    [
        'id' => 7,
        'title' => 'رویداد تیم‌سازی هفته آینده',
        'description' => 'برنامه تیم‌سازی فصل زمستان روز پنجشنبه برگزار می‌شود.',
        'type' => 'event',
        'icon' => 'fa-calendar-star',
        'color' => 'pink',
        'isRead' => true,
        'date' => 'هفته پیش',
        'time' => '۱۴:۰۰',
    ],
];

// شمارش خوانده نشده
$unreadCount = count(array_filter($notifications, fn($n) => !$n['isRead']));

// رنگ‌ها
$colorClasses = [
    'purple' => 'bg-purple-50 text-purple-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'orange' => 'bg-orange-50 text-orange-600',
    'blue' => 'bg-blue-50 text-blue-600',
    'slate' => 'bg-slate-100 text-slate-600',
    'green' => 'bg-green-50 text-green-600',
    'pink' => 'bg-pink-50 text-pink-600',
];

// نام‌های فرستنده برای هر نوع اطلاعیه
$senders = [
    'meeting' => 'مدیریت تیم',
    'announcement' => 'واحد منابع انسانی',
    'reminder' => 'سیستم خودکار',
    'task' => 'احمد محمدی',
    'system' => 'مدیر سیستم',
    'event' => 'واحد روابط عمومی',
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-white">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-24">

    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white shadow-sm px-5 pt-6 pb-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <a href="index.php" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-arrow-right text-slate-600"></i>
          </a>
          <div>
            <h1 class="text-slate-900 text-xl font-bold">اطلاعیه‌ها</h1>
            <?php if ($unreadCount > 0): ?>
            <p class="text-slate-500 text-xs"><?= toPersianNum($unreadCount) ?> اطلاعیه خوانده نشده</p>
            <?php endif; ?>
          </div>
        </div>
        <?php if ($unreadCount > 0): ?>
        <button class="text-blue-600 text-sm font-medium hover:text-blue-700 transition-all">
          علامت‌گذاری همه
        </button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6">

      <?php if (empty($notifications)): ?>
      <!-- Empty State -->
      <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
          <i class="fa-solid fa-bell-slash text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-slate-900 text-base font-semibold mb-2">اطلاعیه‌ای وجود ندارد</h3>
        <p class="text-slate-500 text-sm text-center">در حال حاضر اطلاعیه جدیدی ندارید</p>
      </div>
      <?php else: ?>
      
      <!-- Notifications List -->
      <div class="space-y-3">
        <?php foreach ($notifications as $notification): ?>
        <div onclick="openNotificationModal(<?= htmlspecialchars(json_encode($notification), ENT_QUOTES) ?>, '<?= $senders[$notification['type']] ?>')" 
             class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition-all cursor-pointer active:scale-[0.98] <?= !$notification['isRead'] ? 'border-r-4 border-r-slate-900 bg-slate-50/30' : '' ?>">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $colorClasses[$notification['color']] ?>">
              <i class="fa-solid <?= $notification['icon'] ?>"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2 mb-1">
                <h3 class="text-slate-900 text-sm font-semibold leading-tight <?= !$notification['isRead'] ? 'font-bold' : '' ?>"><?= $notification['title'] ?></h3>
                <?php if (!$notification['isRead']): ?>
                <span class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1.5"></span>
                <?php endif; ?>
              </div>
              <p class="text-slate-600 text-xs leading-relaxed mb-2 line-clamp-2"><?= $notification['description'] ?></p>
              <div class="flex items-center gap-2 text-slate-400 text-xs">
                <span><?= $notification['date'] ?></span>
                <span>•</span>
                <span><?= $notification['time'] ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php endif; ?>

    </div>

    <!-- Bottom Navigation -->
    <?php include PROJECT_ROOT . '/appga/_components/bottom-nav.php'; ?>

  </div>

  <!-- Notification Modal -->
  <div id="notificationModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl transform transition-all">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            <div id="modalIcon" class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"></div>
            <div class="flex-1 min-w-0 pt-1">
              <h2 id="modalTitle" class="text-slate-900 text-base font-bold leading-tight"></h2>
            </div>
          </div>
          <button onclick="closeNotificationModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all flex-shrink-0">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-80px)]">
        
        <!-- Description -->
        <div class="mb-6">
          <p id="modalDescription" class="text-slate-700 text-sm leading-relaxed"></p>
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
              <p id="modalDateTime" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

          <!-- Sender -->
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
              <i class="fa-regular fa-user text-slate-600 text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="text-slate-400 text-xs mb-0.5">فرستنده</p>
              <p id="modalSender" class="text-slate-900 text-sm font-medium"></p>
            </div>
          </div>

        </div>

      </div>

      <!-- Modal Footer (Optional Actions) -->
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <button onclick="closeNotificationModal()" class="w-full h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98]">
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
      'blue': 'bg-blue-50 text-blue-600',
      'slate': 'bg-slate-100 text-slate-600',
      'green': 'bg-green-50 text-green-600',
      'pink': 'bg-pink-50 text-pink-600',
    };

    // باز کردن مودال
    function openNotificationModal(notification, sender) {
      const modal = document.getElementById('notificationModal');
      const modalIcon = document.getElementById('modalIcon');
      const modalTitle = document.getElementById('modalTitle');
      const modalDescription = document.getElementById('modalDescription');
      const modalDateTime = document.getElementById('modalDateTime');
      const modalSender = document.getElementById('modalSender');

      // تنظیم آیکون
      modalIcon.className = `w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 ${colorClasses[notification.color]}`;
      modalIcon.innerHTML = `<i class="fa-solid ${notification.icon}"></i>`;

      // تنظیم محتوا
      modalTitle.textContent = notification.title;
      modalDescription.textContent = notification.description;
      modalDateTime.textContent = `${notification.date} - ${notification.time}`;
      modalSender.textContent = sender;

      // نمایش مودال با انیمیشن
      modal.classList.remove('hidden');
      setTimeout(() => {
        modal.classList.add('animate-fade-in');
      }, 10);
    }

    // بستن مودال
    function closeNotificationModal() {
      const modal = document.getElementById('notificationModal');
      modal.classList.remove('animate-fade-in');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 200);
    }

    // بستن با کلیک روی پس‌زمینه
    document.getElementById('notificationModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeNotificationModal();
      }
    });

    // بستن با کلید ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeNotificationModal();
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

    #notificationModal > div {
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
