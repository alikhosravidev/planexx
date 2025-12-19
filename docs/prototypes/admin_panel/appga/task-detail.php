<?php
/**
 * صفحه جزئیات کار و انجام وظیفه
 * نمایش جزئیات کامل یک وظیفه در سیستم BPMS
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه تسک از URL
$taskId = $_GET['id'] ?? 1;

// تنظیمات صفحه
$pageTitle  = 'جزئیات کار';
$currentTab = 'home';

// اطلاعات کاربر فعلی
$currentUser = [
    'id'       => 5,
    'name'     => 'محمدرضا احمدی',
    'avatar'   => 'https://picsum.photos/seed/avatar1/200/200',
    'position' => 'کارشناس ارشد',
];

// اطلاعات گردش کار (workflow)
$workflow = [
    'id'          => 1,
    'name'        => 'درخواست مرخصی',
    'slug'        => 'leave-request',
    'description' => 'فرایند درخواست و تایید مرخصی کارکنان',
    'department'  => 'منابع انسانی',
    'owner'       => 'زهرا محمدی',
];

// مراحل گردش کار (workflow_states) - 10 مرحله برای تست اسکرول
$workflowStates = [
    ['id' => 1, 'name' => 'ثبت درخواست', 'slug' => 'submit', 'position' => 'start', 'order' => 1],
    ['id' => 2, 'name' => 'بررسی اولیه', 'slug' => 'initial-review', 'position' => 'middle', 'order' => 2],
    ['id' => 3, 'name' => 'تایید سرپرست', 'slug' => 'supervisor-approval', 'position' => 'middle', 'order' => 3],
    ['id' => 4, 'name' => 'بررسی مدیر', 'slug' => 'manager-review', 'position' => 'middle', 'order' => 4],
    ['id' => 5, 'name' => 'تایید مدیر', 'slug' => 'manager-approval', 'position' => 'middle', 'order' => 5],
    ['id' => 6, 'name' => 'بررسی مالی', 'slug' => 'finance-review', 'position' => 'middle', 'order' => 6],
    ['id' => 7, 'name' => 'تایید مالی', 'slug' => 'finance-approval', 'position' => 'middle', 'order' => 7],
    ['id' => 8, 'name' => 'ثبت در سیستم', 'slug' => 'system-register', 'position' => 'middle', 'order' => 8],
    ['id' => 9, 'name' => 'اطلاع‌رسانی', 'slug' => 'notification', 'position' => 'middle', 'order' => 9],
    ['id' => 10, 'name' => 'تکمیل شده', 'slug' => 'completed', 'position' => 'final-success', 'order' => 10],
];

// اطلاعات تسک (bpms_tasks)
$task = [
    'id'                  => $taskId,
    'slug'                => 'LR-2024-0125',
    'title'               => 'درخواست مرخصی ساعتی - احمد رضایی',
    'description'         => 'درخواست مرخصی ساعتی از ساعت ۱۰ صبح تا ۱۴ بعدازظهر روز سه‌شنبه ۲۶ آذر ماه به علت مراجعه به بانک جهت امور مالی.',
    'workflow_id'         => 1,
    'current_state_id'    => 6,
    'assignee_id'         => 5,
    'created_by'          => 12,
    'priority'            => 'high',
    'estimated_hours'     => 2,
    'due_date'            => '۱۴۰۳/۰۹/۲۶ - ساعت ۱۴:۰۰',
    'next_follow_up_date' => '۱۴۰۳/۰۹/۲۵ - ساعت ۱۰:۰۰',
    'created_at'          => '۱۴۰۳/۰۹/۲۳ - ساعت ۰۹:۱۵',
];

// افرادی که می‌توان کار را به آن‌ها ارجاع داد (20 نفر)
$assignableUsers = [
    ['id' => 8, 'name' => 'علی کریمی', 'avatar' => 'https://picsum.photos/seed/user8/200/200', 'is_default' => true],
    ['id' => 9, 'name' => 'فاطمه احمدی', 'avatar' => 'https://picsum.photos/seed/user9/200/200', 'is_default' => false],
    ['id' => 10, 'name' => 'رضا محمودی', 'avatar' => 'https://picsum.photos/seed/user10/200/200', 'is_default' => false],
    ['id' => 11, 'name' => 'زهرا حسینی', 'avatar' => 'https://picsum.photos/seed/user11/200/200', 'is_default' => false],
    ['id' => 12, 'name' => 'محمد صادقی', 'avatar' => 'https://picsum.photos/seed/user12/200/200', 'is_default' => false],
    ['id' => 13, 'name' => 'سارا کاظمی', 'avatar' => 'https://picsum.photos/seed/user13/200/200', 'is_default' => false],
    ['id' => 14, 'name' => 'امیر نوری', 'avatar' => 'https://picsum.photos/seed/user14/200/200', 'is_default' => false],
    ['id' => 15, 'name' => 'مریم عباسی', 'avatar' => 'https://picsum.photos/seed/user15/200/200', 'is_default' => false],
    ['id' => 16, 'name' => 'حسین رضایی', 'avatar' => 'https://picsum.photos/seed/user16/200/200', 'is_default' => false],
    ['id' => 17, 'name' => 'نرگس فرهادی', 'avatar' => 'https://picsum.photos/seed/user17/200/200', 'is_default' => false],
    ['id' => 18, 'name' => 'سعید اکبری', 'avatar' => 'https://picsum.photos/seed/user18/200/200', 'is_default' => false],
    ['id' => 19, 'name' => 'لیلا موسوی', 'avatar' => 'https://picsum.photos/seed/user19/200/200', 'is_default' => false],
    ['id' => 20, 'name' => 'کامران یوسفی', 'avatar' => 'https://picsum.photos/seed/user20/200/200', 'is_default' => false],
    ['id' => 21, 'name' => 'شیما جعفری', 'avatar' => 'https://picsum.photos/seed/user21/200/200', 'is_default' => false],
    ['id' => 22, 'name' => 'بهرام کرمی', 'avatar' => 'https://picsum.photos/seed/user22/200/200', 'is_default' => false],
    ['id' => 23, 'name' => 'آزاده نجفی', 'avatar' => 'https://picsum.photos/seed/user23/200/200', 'is_default' => false],
    ['id' => 24, 'name' => 'پویا شریفی', 'avatar' => 'https://picsum.photos/seed/user24/200/200', 'is_default' => false],
    ['id' => 25, 'name' => 'هانیه قاسمی', 'avatar' => 'https://picsum.photos/seed/user25/200/200', 'is_default' => false],
    ['id' => 26, 'name' => 'مهدی طاهری', 'avatar' => 'https://picsum.photos/seed/user26/200/200', 'is_default' => false],
    ['id' => 27, 'name' => 'نسرین ملکی', 'avatar' => 'https://picsum.photos/seed/user27/200/200', 'is_default' => false],
];

// تاریخچه پیگیری‌ها (bpms_follow_ups)
$followUps = [
    [
        'id'         => 1,
        'type'       => 'state_transition',
        'content'    => 'درخواست ثبت و به سرپرست ارسال شد',
        'created_by' => [
            'id'     => 12,
            'name'   => 'احمد رضایی',
            'avatar' => 'https://picsum.photos/seed/user12/200/200',
        ],
        'previous_state' => 'ثبت درخواست',
        'new_state'      => 'بررسی سرپرست',
        'created_at'     => '۱۴۰۳/۰۹/۲۳ - ۰۹:۱۵',
    ],
    [
        'id'         => 2,
        'type'       => 'follow_up',
        'content'    => 'درخواست بررسی شد. تاریخ و ساعت مرخصی مورد تایید است.',
        'created_by' => [
            'id'     => 7,
            'name'   => 'حسین موسوی',
            'avatar' => 'https://picsum.photos/seed/user7/200/200',
        ],
        'previous_state' => null,
        'new_state'      => null,
        'created_at'     => '۱۴۰۳/۰۹/۲۳ - ۱۱:۳۰',
    ],
    [
        'id'         => 3,
        'type'       => 'state_transition',
        'content'    => 'تایید سرپرست انجام شد و برای تایید نهایی به مدیر ارسال شد',
        'created_by' => [
            'id'     => 7,
            'name'   => 'حسین موسوی',
            'avatar' => 'https://picsum.photos/seed/user7/200/200',
        ],
        'previous_state' => 'بررسی سرپرست',
        'new_state'      => 'تایید مدیر',
        'created_at'     => '۱۴۰۳/۰۹/۲۳ - ۱۲:۰۰',
    ],
    [
        'id'         => 4,
        'type'       => 'user_action',
        'content'    => 'یادآوری: لطفاً درخواست را تا پایان امروز بررسی فرمایید',
        'created_by' => [
            'id'     => 12,
            'name'   => 'احمد رضایی',
            'avatar' => 'https://picsum.photos/seed/user12/200/200',
        ],
        'previous_state' => null,
        'new_state'      => null,
        'created_at'     => '۱۴۰۳/۰۹/۲۴ - ۰۹:۰۰',
    ],
];

// پیوست‌ها
$attachments = [
    [
        'id'          => 1,
        'title'       => 'فرم درخواست مرخصی',
        'filename'    => 'leave-request-form.pdf',
        'type'        => 'pdf',
        'size'        => '۱۲۵ کیلوبایت',
        'uploaded_by' => 'احمد رضایی',
        'uploaded_at' => '۱۴۰۳/۰۹/۲۳',
    ],
    [
        'id'          => 2,
        'title'       => 'مستندات بانکی',
        'filename'    => 'bank-document.jpg',
        'type'        => 'image',
        'size'        => '۳۵۰ کیلوبایت',
        'uploaded_by' => 'احمد رضایی',
        'uploaded_at' => '۱۴۰۳/۰۹/۲۳',
    ],
];

// ناظران (watchers)
$watchers = [
    [
        'id'   => 1,
        'user' => [
            'id'       => 15,
            'name'     => 'مریم حسینی',
            'avatar'   => 'https://picsum.photos/seed/user15/200/200',
            'position' => 'مدیر پروژه',
        ],
        'watch_status' => 'open',
        'watch_reason' => 'نظارت بر مرخصی‌های تیم',
    ],
];

// پیدا کردن مرحله فعلی
$currentStateIndex = 0;

foreach ($workflowStates as $index => $state) {
    if ($state['id'] === $task['current_state_id']) {
        $currentStateIndex = $index;
        break;
    }
}

// آیکون‌ها و رنگ‌های نوع پیگیری
$followUpStyles = [
    'state_transition' => ['icon' => 'fa-arrow-right-arrow-left', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
    'follow_up'        => ['icon' => 'fa-comment', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
    'user_action'      => ['icon' => 'fa-hand-pointer', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
    'watcher_review'   => ['icon' => 'fa-eye', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
];

// رنگ‌های اولویت
$priorityStyles = [
    'low'    => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'کم'],
    'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'متوسط'],
    'high'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'بالا'],
    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'فوری'],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-white">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-gray-100 min-h-screen shadow-xl relative pb-32">

    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white shadow-sm">
      <div class="px-5 pt-6 pb-4">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-3">
            <a href="javascript:history.back()" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
              <i class="fa-solid fa-arrow-right text-slate-600"></i>
            </a>
            <div>
              <h1 class="text-slate-900 text-lg font-bold">جزئیات کار</h1>
              <p class="text-slate-500 text-xs"><?= $task['slug'] ?></p>
            </div>
          </div>
          <button onclick="openTaskInfoModal()" class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-circle-info text-slate-600"></i>
          </button>
        </div>

        <!-- Priority Badge & Deadline -->
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="<?= $priorityStyles[$task['priority']]['bg'] ?> <?= $priorityStyles[$task['priority']]['text'] ?> px-3 py-1.5 rounded-lg text-xs font-semibold">
              <i class="fa-solid fa-flag ml-1"></i>
              <?= $priorityStyles[$task['priority']]['label'] ?>
            </span>
            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
              <i class="fa-solid fa-diagram-project ml-1"></i>
              <?= $workflow['name'] ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Workflow Progress - Scrollable -->
      <div class="px-5 pb-4">
        <div id="workflowProgress" class="bg-slate-50 rounded-2xl p-3 overflow-x-auto scrollbar-hide">
          <div class="flex items-center gap-2" style="min-width: max-content;">
            <?php foreach ($workflowStates as $index => $state): ?>
            <div class="flex items-center gap-2 flex-shrink-0" <?= $index === $currentStateIndex ? 'id="currentStep"' : '' ?>>
              <!-- Step Circle -->
              <div class="flex flex-col items-center">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all
                  <?php if ($index < $currentStateIndex): ?>
                    bg-slate-900 text-white
                  <?php elseif ($index === $currentStateIndex): ?>
                    bg-slate-900 text-white ring-4 ring-slate-300
                  <?php else: ?>
                    bg-gray-200 text-slate-500
                  <?php endif; ?>
                ">
                  <?php if ($index < $currentStateIndex): ?>
                    <i class="fa-solid fa-check text-xs"></i>
                  <?php else: ?>
                    <?= toPersianNum($index + 1) ?>
                  <?php endif; ?>
                </div>
                <span class="text-[10px] text-center mt-1.5 whitespace-nowrap max-w-[70px] truncate
                  <?= $index === $currentStateIndex ? 'text-slate-900 font-bold' : 'text-slate-500' ?>
                "><?= $state['name'] ?></span>
              </div>
              
              <!-- Connector Line -->
              <?php if ($index < count($workflowStates) - 1): ?>
              <div class="w-8 h-1 rounded-full flex-shrink-0 mt-[-18px] <?= $index < $currentStateIndex ? 'bg-slate-900' : 'bg-gray-200' ?>"></div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-4 space-y-4">

      <!-- Deadline Alert -->
      <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-4 text-white">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
            <i class="fa-solid fa-clock text-2xl"></i>
          </div>
          <div class="flex-1">
            <p class="text-white/80 text-xs mb-0.5">مهلت تکمیل</p>
            <p class="text-white font-bold"><?= $task['due_date'] ?></p>
          </div>
          <div class="text-left">
            <span class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg text-xs font-semibold">
              ۲ روز مانده
            </span>
          </div>
        </div>
      </div>

      <!-- Task Details -->
      <div class="bg-white rounded-2xl p-5 shadow-sm">
        <h2 class="text-slate-900 text-base font-bold mb-3 leading-relaxed"><?= $task['title'] ?></h2>
        <p class="text-slate-600 text-sm leading-relaxed mb-4"><?= $task['description'] ?></p>
        
        <!-- Meta Info -->
        <div class="space-y-3 pt-4 border-t border-gray-100">
          <div class="flex items-center justify-between">
            <span class="text-slate-500 text-xs">ایجاد کننده</span>
            <div class="flex items-center gap-2">
              <img src="https://picsum.photos/seed/user12/200/200" alt="" class="w-6 h-6 rounded-full">
              <span class="text-slate-900 text-sm font-medium">احمد رضایی</span>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 text-xs">تاریخ ایجاد</span>
            <span class="text-slate-900 text-sm font-medium"><?= $task['created_at'] ?></span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 text-xs">پیگیری بعدی</span>
            <span class="text-slate-900 text-sm font-medium"><?= $task['next_follow_up_date'] ?></span>
          </div>
        </div>
      </div>

      <!-- Attachments -->
      <?php if (!empty($attachments)): ?>
      <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-slate-900 text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-paperclip text-slate-600"></i>
            پیوست‌ها
          </h3>
          <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-xs font-medium">
            <?= toPersianNum(count($attachments)) ?> فایل
          </span>
        </div>
        <div class="space-y-2">
          <?php foreach ($attachments as $attachment): ?>
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all cursor-pointer">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 
              <?= $attachment['type']                    === 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' ?>">
              <i class="fa-solid <?= $attachment['type'] === 'pdf' ? 'fa-file-pdf' : 'fa-image' ?>"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="text-slate-900 text-sm font-medium truncate"><?= $attachment['title'] ?></h4>
              <p class="text-slate-500 text-xs"><?= $attachment['size'] ?> • <?= $attachment['uploaded_by'] ?></p>
            </div>
            <i class="fa-solid fa-download text-slate-400"></i>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- History Timeline -->
      <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-slate-900 text-sm font-bold flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
            تاریخچه اقدامات
          </h3>
        </div>
        
        <div class="relative">
          <!-- Timeline Line -->
          <div class="absolute top-0 bottom-0 right-5 w-0.5 bg-gray-200"></div>
          
          <!-- Timeline Items -->
          <div class="space-y-4">
            <?php foreach ($followUps as $index => $followUp):
                $style = $followUpStyles[$followUp['type']];
                ?>
            <div class="relative flex gap-4 pr-2">
              <!-- Timeline Dot -->
              <div class="w-10 h-10 rounded-xl <?= $style['bg'] ?> flex items-center justify-center flex-shrink-0 z-10 border-2 <?= $style['border'] ?>">
                <i class="fa-solid <?= $style['icon'] ?> <?= $style['text'] ?> text-sm"></i>
              </div>
              
              <!-- Content -->
              <div class="flex-1 pb-4 <?= $index !== count($followUps) - 1 ? 'border-b border-gray-100' : '' ?>">
                <div class="flex items-start justify-between gap-2 mb-1">
                  <div class="flex items-center gap-2">
                    <img src="<?= $followUp['created_by']['avatar'] ?>" alt="" class="w-5 h-5 rounded-full">
                    <span class="text-slate-900 text-xs font-semibold"><?= $followUp['created_by']['name'] ?></span>
                  </div>
                  <span class="text-slate-400 text-[10px]"><?= $followUp['created_at'] ?></span>
                </div>
                
                <?php if ($followUp['type'] === 'state_transition'): ?>
                <div class="flex items-center gap-2 mb-2">
                  <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[10px] font-medium"><?= $followUp['previous_state'] ?></span>
                  <i class="fa-solid fa-arrow-left text-slate-400 text-[10px]"></i>
                  <span class="bg-slate-900 text-white px-2 py-1 rounded text-[10px] font-medium"><?= $followUp['new_state'] ?></span>
                </div>
                <?php endif; ?>
                
                <p class="text-slate-600 text-xs leading-relaxed"><?= $followUp['content'] ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>

    <!-- Floating Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50">
      <div class="max-w-[480px] mx-auto bg-white border-t border-gray-200 px-5 py-4 shadow-lg">
        <div class="flex items-center gap-3">
          <button onclick="openFollowUpModal()" class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <i class="fa-solid fa-comment"></i>
            ثبت یادداشت
          </button>
          <button onclick="openForwardModal()" class="flex-1 h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            ارجاع به مرحله بعد
          </button>
        </div>
      </div>
    </div>

  </div>

  <!-- Follow-up Modal -->
  <div id="followUpModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <h2 class="text-slate-900 text-lg font-bold">ثبت یادداشت و پیگیری</h2>
          <button onclick="closeFollowUpModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
        
        <!-- Follow-up Content -->
        <div class="mb-5">
          <label class="text-slate-700 text-sm font-medium mb-2 block">متن یادداشت</label>
          <textarea id="followUpContent" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 resize-none focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="یادداشت یا توضیحات خود را بنویسید..."></textarea>
        </div>

        <!-- Next Follow-up Date -->
        <div class="mb-5">
          <label class="text-slate-700 text-sm font-medium mb-2 block">تاریخ پیگیری بعدی</label>
          <div class="relative">
            <input type="text" id="nextFollowUpDate" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="۱۴۰۳/۰۹/۲۷">
            <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
          </div>
        </div>

        <!-- Attach File -->
        <div class="mb-5">
          <label class="text-slate-700 text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
          <label class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-slate-400 transition-all block">
            <input type="file" id="followUpFile" class="hidden" onchange="handleFileSelect(this)">
            <div id="fileUploadPlaceholder">
              <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-2xl mb-2"></i>
              <p class="text-slate-500 text-sm">برای آپلود فایل کلیک کنید</p>
            </div>
            <div id="fileUploadSelected" class="hidden">
              <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
              <p id="selectedFileName" class="text-slate-700 text-sm font-medium"></p>
              <p class="text-slate-400 text-xs mt-1">برای تغییر کلیک کنید</p>
            </div>
          </label>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <div class="grid grid-cols-2 gap-3">
          <button onclick="closeFollowUpModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
            انصراف
          </button>
          <button onclick="submitFollowUp()" class="h-12 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i>
            ثبت یادداشت
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Forward Modal -->
  <div id="forwardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[90vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-slate-900 text-lg font-bold">ارجاع به مرحله بعد</h2>
            <p class="text-slate-500 text-xs mt-0.5">ثبت در سیستم</p>
          </div>
          <button onclick="closeForwardModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
        
        <!-- Forward Note (moved to top) -->
        <div class="mb-5">
          <label class="text-slate-700 text-sm font-medium mb-2 block">توضیحات ارجاع</label>
          <textarea id="forwardNote" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-900 resize-none focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400" placeholder="توضیحات لازم برای نفر بعدی..."></textarea>
        </div>

        <!-- Select Assignee - Compact Grid -->
        <div class="mb-5">
          <label class="text-slate-700 text-sm font-medium mb-3 block">انتخاب مسئول بعدی</label>
          <div class="grid grid-cols-3 gap-3 max-h-[300px] overflow-y-auto p-1">
            <?php foreach ($assignableUsers as $user): ?>
            <label class="flex flex-col items-center p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-all has-[:checked]:bg-slate-900 has-[:checked]:ring-2 has-[:checked]:ring-slate-400 group relative">
              <input type="radio" name="assignee" value="<?= $user['id'] ?>" class="hidden" <?= $user['is_default'] ? 'checked' : '' ?>>
              <?php if ($user['is_default']): ?>
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-star text-white text-[9px]"></i>
              </span>
              <?php endif; ?>
              <img src="<?= $user['avatar'] ?>" alt="" class="w-12 h-12 rounded-full mb-2 ring-2 ring-transparent group-has-[:checked]:ring-white">
              <span class="text-xs text-center font-medium text-slate-700 group-has-[:checked]:text-white leading-tight line-clamp-2"><?= $user['name'] ?></span>
              <div class="absolute inset-0 rounded-xl group-has-[:checked]:bg-slate-900/0"></div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <div class="grid grid-cols-2 gap-3">
          <button onclick="closeForwardModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all active:scale-[0.98]">
            انصراف
          </button>
          <button onclick="submitForward()" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            ارجاع کار
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Task Info Modal -->
  <div id="taskInfoModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-[480px] max-h-[85vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <h2 class="text-slate-900 text-lg font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-slate-600"></i>
            اطلاعات تکمیلی کار
          </h2>
          <button onclick="closeTaskInfoModal()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(85vh-80px)]">
        
        <!-- Task ID -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-hashtag text-white text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">شناسه کار</p>
            <p class="text-slate-900 text-sm font-bold"><?= $task['slug'] ?></p>
          </div>
        </div>

        <!-- Creator -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <img src="https://picsum.photos/seed/user12/200/200" alt="" class="w-10 h-10 rounded-full">
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">ایجاد کننده</p>
            <p class="text-slate-900 text-sm font-semibold">احمد رضایی</p>
          </div>
        </div>

        <!-- Created Date -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <i class="fa-regular fa-calendar text-blue-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">تاریخ ایجاد</p>
            <p class="text-slate-900 text-sm font-medium"><?= $task['created_at'] ?></p>
          </div>
        </div>

        <!-- Workflow -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-diagram-project text-purple-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">گردش کار</p>
            <p class="text-slate-900 text-sm font-medium"><?= $workflow['name'] ?></p>
          </div>
        </div>

        <!-- Department -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-building text-amber-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">دپارتمان</p>
            <p class="text-slate-900 text-sm font-medium"><?= $workflow['department'] ?></p>
          </div>
        </div>

        <!-- Workflow Owner -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-user-tie text-green-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">مالک فرایند</p>
            <p class="text-slate-900 text-sm font-medium"><?= $workflow['owner'] ?></p>
          </div>
        </div>

        <!-- Priority -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 <?= $priorityStyles[$task['priority']]['bg'] ?> rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-flag <?= $priorityStyles[$task['priority']]['text'] ?> text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">اولویت</p>
            <p class="text-slate-900 text-sm font-medium"><?= $priorityStyles[$task['priority']]['label'] ?></p>
          </div>
        </div>

        <!-- Estimated Hours -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
          <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
            <i class="fa-regular fa-clock text-cyan-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <p class="text-slate-400 text-xs mb-0.5">زمان تخمینی</p>
            <p class="text-slate-900 text-sm font-medium"><?= toPersianNum($task['estimated_hours']) ?> ساعت</p>
          </div>
        </div>

        <!-- Watchers Section -->
        <?php if (!empty($watchers)): ?>
        <div class="mt-5 pt-4 border-t border-gray-100">
          <h4 class="text-slate-700 text-sm font-semibold mb-3 flex items-center gap-2">
            <i class="fa-solid fa-eye text-slate-500"></i>
            ناظران کار
          </h4>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($watchers as $watcher): ?>
            <div class="flex items-center gap-2 bg-slate-100 rounded-full pl-3 pr-1 py-1">
              <img src="<?= $watcher['user']['avatar'] ?>" alt="" class="w-6 h-6 rounded-full">
              <span class="text-slate-700 text-xs font-medium"><?= $watcher['user']['name'] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  
  <script>
    // Auto-scroll workflow progress to center current step
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.getElementById('workflowProgress');
      const currentStep = document.getElementById('currentStep');
      
      if (container && currentStep) {
        // Calculate scroll position to center the current step
        const containerWidth = container.offsetWidth;
        const stepLeft = currentStep.offsetLeft;
        const stepWidth = currentStep.offsetWidth;
        
        // Scroll to position current step in center
        const scrollPosition = stepLeft - (containerWidth / 2) + (stepWidth / 2);
        
        // Smooth scroll to center
        container.scrollTo({
          left: scrollPosition,
          behavior: 'smooth'
        });
      }
    });
    
    // File upload handler
    function handleFileSelect(input) {
      const placeholder = document.getElementById('fileUploadPlaceholder');
      const selected = document.getElementById('fileUploadSelected');
      const fileName = document.getElementById('selectedFileName');
      
      if (input.files && input.files[0]) {
        placeholder.classList.add('hidden');
        selected.classList.remove('hidden');
        fileName.textContent = input.files[0].name;
      }
    }
    
    // Follow-up Modal
    function openFollowUpModal() {
      document.getElementById('followUpModal').classList.remove('hidden');
    }
    
    function closeFollowUpModal() {
      document.getElementById('followUpModal').classList.add('hidden');
      // Reset file upload
      document.getElementById('fileUploadPlaceholder').classList.remove('hidden');
      document.getElementById('fileUploadSelected').classList.add('hidden');
      document.getElementById('followUpFile').value = '';
    }
    
    function submitFollowUp() {
      const content = document.getElementById('followUpContent').value;
      const nextDate = document.getElementById('nextFollowUpDate').value;
      
      if (!content.trim()) {
        alert('لطفاً متن یادداشت را وارد کنید');
        return;
      }
      
      // Simulate submission
      alert('یادداشت با موفقیت ثبت شد');
      closeFollowUpModal();
      location.reload();
    }
    
    // Forward Modal
    function openForwardModal() {
      document.getElementById('forwardModal').classList.remove('hidden');
    }
    
    function closeForwardModal() {
      document.getElementById('forwardModal').classList.add('hidden');
    }
    
    function submitForward() {
      const assignee = document.querySelector('input[name="assignee"]:checked');
      const note = document.getElementById('forwardNote').value;
      
      if (!assignee) {
        alert('لطفاً مسئول بعدی را انتخاب کنید');
        return;
      }
      
      // Simulate submission
      alert('کار با موفقیت ارجاع داده شد');
      closeForwardModal();
      // Redirect to tasks list
      window.location.href = 'tasks.php';
    }
    
    // Task Info Modal
    function openTaskInfoModal() {
      document.getElementById('taskInfoModal').classList.remove('hidden');
    }
    
    function closeTaskInfoModal() {
      document.getElementById('taskInfoModal').classList.add('hidden');
    }
    
    // Close modals on backdrop click
    document.querySelectorAll('#followUpModal, #forwardModal, #taskInfoModal').forEach(modal => {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.add('hidden');
        }
      });
    });
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('#followUpModal, #forwardModal, #taskInfoModal').forEach(modal => {
          modal.classList.add('hidden');
        });
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

    #followUpModal > div,
    #forwardModal > div,
    #taskInfoModal > div {
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
