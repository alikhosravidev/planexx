<?php
/**
 * صفحه جزئیات کار - ماژول مدیریت وظایف
 * نمایش جزئیات کامل یک وظیفه در سیستم BPMS
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه تسک از URL
$taskId = $_GET['id'] ?? 1;

// تنظیمات صفحه
$pageTitle = 'جزئیات کار';
$currentPage = 'workflow';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'ویرایش کار', 'url' => '#', 'icon' => 'fa-solid fa-pen', 'type' => 'secondary', 'onclick' => 'openEditModal()'],
];

// اطلاعات کاربر فعلی
$currentUser = [
    'id' => 5,
    'name' => 'محمدرضا احمدی',
    'avatar' => 'https://picsum.photos/seed/avatar1/200/200',
    'position' => 'کارشناس ارشد',
];

// اطلاعات گردش کار (workflow)
$workflow = [
    'id' => 1,
    'name' => 'اینسپکشن',
    'slug' => 'inception',
    'description' => 'فرایند اینسپکشن و ارزیابی اولیه مشتریان',
    'department' => 'فروش',
    'owner' => 'فاطمه نوری',
];

// مراحل گردش کار (workflow_states)
$workflowStates = [
    ['id' => 1, 'name' => 'آزمون ورودی', 'slug' => 'entry-test', 'position' => 'start', 'order' => 1, 'color' => '#E0F2F1'],
    ['id' => 2, 'name' => 'مشاوره خصوصی', 'slug' => 'private-consult', 'position' => 'middle', 'order' => 2, 'color' => '#E3F2FD'],
    ['id' => 3, 'name' => 'منتظر پرداخت', 'slug' => 'waiting-payment', 'position' => 'middle', 'order' => 3, 'color' => '#FFF3E0'],
    ['id' => 4, 'name' => 'پیش پرداخت شده', 'slug' => 'prepaid', 'position' => 'middle', 'order' => 4, 'color' => '#E8F5E9'],
    ['id' => 5, 'name' => 'تکمیل شده', 'slug' => 'completed', 'position' => 'final-success', 'order' => 5, 'color' => '#C8E6C9'],
];

// اطلاعات تسک (bpms_tasks)
$task = [
    'id' => $taskId,
    'slug' => 'INS-2024-001',
    'title' => 'مشاوره آقای محمدی - شرکت آلفا',
    'description' => 'جلسه مشاوره اولیه با مدیرعامل شرکت آلفا برای بررسی نیازمندی‌ها و ارائه پیشنهادات اولیه. نیاز به آماده‌سازی مستندات فنی و تجاری پیش از جلسه.',
    'workflow_id' => 1,
    'current_state_id' => 2,
    'assignee_id' => 1,
    'created_by' => 5,
    'priority' => 'urgent',
    'estimated_hours' => 4,
    'due_date' => '۱۴۰۳/۰۹/۲۷ - ساعت ۱۴:۰۰',
    'next_follow_up_date' => '۱۴۰۳/۰۹/۲۶ - ساعت ۱۰:۰۰',
    'created_at' => '۱۴۰۳/۰۹/۲۰ - ساعت ۰۹:۱۵',
];

// مسئول فعلی
$assignee = [
    'id' => 1,
    'name' => 'علی احمدی',
    'avatar' => 'https://picsum.photos/seed/user1/200/200',
    'position' => 'کارشناس فروش',
    'department' => 'فروش',
];

// ایجاد کننده
$creator = [
    'id' => 5,
    'name' => 'فاطمه نوری',
    'avatar' => 'https://picsum.photos/seed/user5/200/200',
    'position' => 'مدیر فروش',
];

// افرادی که می‌توان کار را به آن‌ها ارجاع داد
$assignableUsers = [
    ['id' => 1, 'name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200', 'position' => 'کارشناس فروش', 'is_default' => true],
    ['id' => 2, 'name' => 'مریم رضایی', 'avatar' => 'https://picsum.photos/seed/user2/200/200', 'position' => 'حسابدار', 'is_default' => false],
    ['id' => 3, 'name' => 'سارا محمدی', 'avatar' => 'https://picsum.photos/seed/user3/200/200', 'position' => 'کارشناس HR', 'is_default' => false],
    ['id' => 4, 'name' => 'رضا کریمی', 'avatar' => 'https://picsum.photos/seed/user4/200/200', 'position' => 'توسعه‌دهنده', 'is_default' => false],
    ['id' => 5, 'name' => 'فاطمه نوری', 'avatar' => 'https://picsum.photos/seed/user5/200/200', 'position' => 'مدیر فروش', 'is_default' => false],
    ['id' => 6, 'name' => 'حسین موسوی', 'avatar' => 'https://picsum.photos/seed/user6/200/200', 'position' => 'مدیر HR', 'is_default' => false],
    ['id' => 7, 'name' => 'زهرا کاظمی', 'avatar' => 'https://picsum.photos/seed/user7/200/200', 'position' => 'مدیر عملیات', 'is_default' => false],
    ['id' => 8, 'name' => 'امیر حسینی', 'avatar' => 'https://picsum.photos/seed/user8/200/200', 'position' => 'مدیر مالی', 'is_default' => false],
];

// تاریخچه پیگیری‌ها (bpms_follow_ups)
$followUps = [
    [
        'id' => 1,
        'type' => 'state_transition',
        'content' => 'کار ایجاد و به مرحله آزمون ورودی منتقل شد',
        'created_by' => [
            'id' => 5,
            'name' => 'فاطمه نوری',
            'avatar' => 'https://picsum.photos/seed/user5/200/200',
        ],
        'previous_state' => null,
        'new_state' => 'آزمون ورودی',
        'created_at' => '۱۴۰۳/۰۹/۲۰ - ۰۹:۱۵',
    ],
    [
        'id' => 2,
        'type' => 'follow_up',
        'content' => 'تماس اولیه با مشتری انجام شد. ایشان آمادگی شرکت در جلسه مشاوره را اعلام کردند.',
        'created_by' => [
            'id' => 1,
            'name' => 'علی احمدی',
            'avatar' => 'https://picsum.photos/seed/user1/200/200',
        ],
        'previous_state' => null,
        'new_state' => null,
        'created_at' => '۱۴۰۳/۰۹/۲۱ - ۱۱:۳۰',
    ],
    [
        'id' => 3,
        'type' => 'state_transition',
        'content' => 'آزمون ورودی با موفقیت انجام شد و به مرحله مشاوره خصوصی منتقل شد',
        'created_by' => [
            'id' => 1,
            'name' => 'علی احمدی',
            'avatar' => 'https://picsum.photos/seed/user1/200/200',
        ],
        'previous_state' => 'آزمون ورودی',
        'new_state' => 'مشاوره خصوصی',
        'previous_assignee' => null,
        'new_assignee' => ['id' => 1, 'name' => 'علی احمدی'],
        'created_at' => '۱۴۰۳/۰۹/۲۲ - ۱۴:۰۰',
    ],
    [
        'id' => 4,
        'type' => 'user_action',
        'content' => 'یادآوری: جلسه مشاوره فردا ساعت ۱۰ صبح برگزار می‌شود. لطفاً مستندات را آماده کنید.',
        'created_by' => [
            'id' => 5,
            'name' => 'فاطمه نوری',
            'avatar' => 'https://picsum.photos/seed/user5/200/200',
        ],
        'previous_state' => null,
        'new_state' => null,
        'created_at' => '۱۴۰۳/۰۹/۲۵ - ۱۶:۰۰',
    ],
];

// پیوست‌ها
$attachments = [
    [
        'id' => 1,
        'title' => 'پروپوزال اولیه',
        'filename' => 'proposal-v1.pdf',
        'type' => 'pdf',
        'size' => '۲.۵ مگابایت',
        'uploaded_by' => 'فاطمه نوری',
        'uploaded_at' => '۱۴۰۳/۰۹/۲۰',
    ],
    [
        'id' => 2,
        'title' => 'نتایج آزمون ورودی',
        'filename' => 'test-results.xlsx',
        'type' => 'excel',
        'size' => '۱۲۵ کیلوبایت',
        'uploaded_by' => 'علی احمدی',
        'uploaded_at' => '۱۴۰۳/۰۹/۲۲',
    ],
];

// ناظران (watchers)
$watchers = [
    [
        'id' => 1,
        'user' => [
            'id' => 5,
            'name' => 'فاطمه نوری',
            'avatar' => 'https://picsum.photos/seed/user5/200/200',
            'position' => 'مدیر فروش',
        ],
        'watch_status' => 'open',
        'watch_reason' => 'نظارت بر پیشرفت کار',
    ],
    [
        'id' => 2,
        'user' => [
            'id' => 8,
            'name' => 'امیر حسینی',
            'avatar' => 'https://picsum.photos/seed/user8/200/200',
            'position' => 'مدیر مالی',
        ],
        'watch_status' => 'open',
        'watch_reason' => 'نظارت مالی',
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
$currentState = $workflowStates[$currentStateIndex];

// آیکون‌ها و رنگ‌های نوع پیگیری
$followUpStyles = [
    'state_transition' => ['icon' => 'fa-arrow-right-arrow-left', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
    'follow_up' => ['icon' => 'fa-comment', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
    'user_action' => ['icon' => 'fa-hand-pointer', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
    'watcher_review' => ['icon' => 'fa-eye', 'bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
];

// رنگ‌های اولویت
$priorityStyles = [
    'low' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'کم', 'gradient' => 'from-slate-500 to-slate-600'],
    'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'متوسط', 'gradient' => 'from-blue-500 to-blue-600'],
    'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'بالا', 'gradient' => 'from-orange-500 to-orange-600'],
    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'فوری', 'gradient' => 'from-red-500 to-red-600'],
];

// بردکرامب
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'کارها و وظایف', 'url' => '/dashboard/workflows/tasks.php'],
    ['label' => $task['slug']],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle . ' - ' . $task['slug']]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle' => $task['slug'],
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          
          <!-- Right Column - Main Content (2/3) -->
          <div class="xl:col-span-2 space-y-6">
            
            <!-- Task Header Card -->
            <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
              
              <!-- Workflow Progress Bar -->
              <div class="px-6 py-4 bg-slate-50 border-b border-border-light">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="text-sm font-semibold text-text-primary flex items-center gap-2">
                    <i class="fa-solid fa-diagram-project text-indigo-600"></i>
                    مراحل فرایند
                  </h3>
                  <span class="text-xs text-text-muted">
                    مرحله <?= toPersianNum($currentStateIndex + 1) ?> از <?= toPersianNum(count($workflowStates)) ?>
                  </span>
                </div>
                <div id="workflowProgress" class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
                  <?php foreach ($workflowStates as $index => $state): ?>
                  <div class="flex items-center gap-2 flex-shrink-0" <?= $index === $currentStateIndex ? 'id="currentStep"' : '' ?>>
                    <div class="flex flex-col items-center">
                      <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all
                        <?php if ($index < $currentStateIndex): ?>
                          bg-indigo-600 text-white
                        <?php elseif ($index === $currentStateIndex): ?>
                          bg-indigo-600 text-white ring-4 ring-indigo-200
                        <?php else: ?>
                          bg-gray-200 text-slate-500
                        <?php endif; ?>
                      ">
                        <?php if ($index < $currentStateIndex): ?>
                          <i class="fa-solid fa-check text-sm"></i>
                        <?php else: ?>
                          <?= toPersianNum($index + 1) ?>
                        <?php endif; ?>
                      </div>
                      <span class="text-xs text-center mt-2 whitespace-nowrap max-w-[80px] truncate
                        <?= $index === $currentStateIndex ? 'text-indigo-600 font-bold' : 'text-slate-500' ?>
                      "><?= $state['name'] ?></span>
                    </div>
                    
                    <?php if ($index < count($workflowStates) - 1): ?>
                    <div class="w-12 h-1 rounded-full flex-shrink-0 mt-[-24px] <?= $index < $currentStateIndex ? 'bg-indigo-600' : 'bg-gray-200' ?>"></div>
                    <?php endif; ?>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <!-- Task Title & Description -->
              <div class="p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                      <span class="<?= $priorityStyles[$task['priority']]['bg'] ?> <?= $priorityStyles[$task['priority']]['text'] ?> px-3 py-1.5 rounded-lg text-xs font-semibold">
                        <i class="fa-solid fa-flag ml-1"></i>
                        <?= $priorityStyles[$task['priority']]['label'] ?>
                      </span>
                      <span class="bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-medium">
                        <i class="fa-solid fa-diagram-project ml-1"></i>
                        <?= $workflow['name'] ?>
                      </span>
                      <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
                        <i class="fa-solid fa-building ml-1"></i>
                        <?= $workflow['department'] ?>
                      </span>
                    </div>
                    <h1 class="text-xl font-bold text-text-primary mb-3 leading-relaxed"><?= $task['title'] ?></h1>
                    <p class="text-text-secondary text-base leading-relaxed"><?= $task['description'] ?></p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Deadline Alert -->
            <div class="bg-gradient-to-r <?= $priorityStyles[$task['priority']]['gradient'] ?> rounded-2xl p-5 text-white">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0">
                  <i class="fa-solid fa-clock text-2xl"></i>
                </div>
                <div class="flex-1">
                  <p class="text-white/80 text-sm mb-1">مهلت تکمیل کار</p>
                  <p class="text-white text-lg font-bold"><?= $task['due_date'] ?></p>
                </div>
                <div class="text-left flex-shrink-0">
                  <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl text-sm font-semibold">
                    ۲ روز مانده
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Attachments -->
            <?php if (!empty($attachments)): ?>
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
                  <i class="fa-solid fa-paperclip text-slate-600"></i>
                  پیوست‌ها
                </h3>
                <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                  <i class="fa-solid fa-plus ml-1"></i>
                  افزودن پیوست
                </button>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php foreach ($attachments as $attachment): ?>
                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all cursor-pointer group">
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 
                    <?php 
                    switch($attachment['type']) {
                        case 'pdf': echo 'bg-red-100 text-red-600'; break;
                        case 'excel': echo 'bg-green-100 text-green-600'; break;
                        default: echo 'bg-blue-100 text-blue-600';
                    }
                    ?>">
                    <i class="fa-solid <?php 
                    switch($attachment['type']) {
                        case 'pdf': echo 'fa-file-pdf'; break;
                        case 'excel': echo 'fa-file-excel'; break;
                        default: echo 'fa-file';
                    }
                    ?> text-lg"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="text-text-primary text-sm font-medium truncate"><?= $attachment['title'] ?></h4>
                    <p class="text-text-muted text-xs"><?= $attachment['size'] ?> • <?= $attachment['uploaded_by'] ?></p>
                  </div>
                  <i class="fa-solid fa-download text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- History Timeline -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
                  <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                  تاریخچه اقدامات
                </h3>
                <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-medium">
                  <?= toPersianNum(count($followUps)) ?> رویداد
                </span>
              </div>
              
              <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute top-0 bottom-0 right-6 w-0.5 bg-gray-200"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-5">
                  <?php foreach ($followUps as $index => $followUp): 
                    $style = $followUpStyles[$followUp['type']];
                  ?>
                  <div class="relative flex gap-4 pr-2">
                    <!-- Timeline Dot -->
                    <div class="w-12 h-12 rounded-xl <?= $style['bg'] ?> flex items-center justify-center flex-shrink-0 z-10 border-2 <?= $style['border'] ?>">
                      <i class="fa-solid <?= $style['icon'] ?> <?= $style['text'] ?>"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 pb-4 <?= $index !== count($followUps) - 1 ? 'border-b border-gray-100' : '' ?>">
                      <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2">
                          <img src="<?= $followUp['created_by']['avatar'] ?>" alt="" class="w-6 h-6 rounded-full">
                          <span class="text-text-primary text-sm font-semibold"><?= $followUp['created_by']['name'] ?></span>
                        </div>
                        <span class="text-text-muted text-xs"><?= $followUp['created_at'] ?></span>
                      </div>
                      
                      <?php if ($followUp['type'] === 'state_transition'): ?>
                      <div class="flex items-center gap-2 mb-2">
                        <?php if ($followUp['previous_state']): ?>
                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded text-xs font-medium"><?= $followUp['previous_state'] ?></span>
                        <i class="fa-solid fa-arrow-left text-slate-400 text-xs"></i>
                        <?php endif; ?>
                        <span class="bg-indigo-600 text-white px-2.5 py-1 rounded text-xs font-medium"><?= $followUp['new_state'] ?></span>
                      </div>
                      <?php endif; ?>
                      
                      <p class="text-text-secondary text-sm leading-relaxed"><?= $followUp['content'] ?></p>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            
          </div>
          
          <!-- Left Column - Sidebar (1/3) -->
          <div class="space-y-6">
            
            <!-- Action Buttons -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
              <div class="space-y-3">
                <button onclick="openForwardModal()" class="w-full h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2 shadow-sm">
                  <i class="fa-solid fa-paper-plane"></i>
                  ارجاع به مرحله بعد
                </button>
                <button onclick="openFollowUpModal()" class="w-full h-12 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                  <i class="fa-solid fa-comment"></i>
                  ثبت یادداشت
                </button>
              </div>
            </div>
            
            <!-- Task Info -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
              <h3 class="text-base font-bold text-text-primary mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-slate-600"></i>
                اطلاعات کار
              </h3>
              
              <div class="space-y-4">
                <!-- Assignee -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <img src="<?= $assignee['avatar'] ?>" alt="" class="w-10 h-10 rounded-full">
                  <div class="flex-1 min-w-0">
                    <p class="text-text-muted text-xs mb-0.5">مسئول فعلی</p>
                    <p class="text-text-primary text-sm font-semibold truncate"><?= $assignee['name'] ?></p>
                    <p class="text-text-muted text-xs"><?= $assignee['position'] ?></p>
                  </div>
                </div>
                
                <!-- Creator -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <img src="<?= $creator['avatar'] ?>" alt="" class="w-10 h-10 rounded-full">
                  <div class="flex-1 min-w-0">
                    <p class="text-text-muted text-xs mb-0.5">ایجاد کننده</p>
                    <p class="text-text-primary text-sm font-semibold truncate"><?= $creator['name'] ?></p>
                    <p class="text-text-muted text-xs"><?= $creator['position'] ?></p>
                  </div>
                </div>
                
                <!-- Current State -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-layer-group text-indigo-600"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">مرحله فعلی</p>
                    <p class="text-text-primary text-sm font-semibold"><?= $currentState['name'] ?></p>
                  </div>
                </div>
                
                <!-- Created Date -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-regular fa-calendar text-blue-600"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">تاریخ ایجاد</p>
                    <p class="text-text-primary text-sm font-medium"><?= $task['created_at'] ?></p>
                  </div>
                </div>
                
                <!-- Next Follow-up -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-bell text-amber-600"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">پیگیری بعدی</p>
                    <p class="text-text-primary text-sm font-medium"><?= $task['next_follow_up_date'] ?></p>
                  </div>
                </div>
                
                <!-- Estimated Hours -->
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-regular fa-clock text-cyan-600"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-text-muted text-xs mb-0.5">زمان تخمینی</p>
                    <p class="text-text-primary text-sm font-medium"><?= toPersianNum($task['estimated_hours']) ?> ساعت</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Watchers -->
            <?php if (!empty($watchers)): ?>
            <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-text-primary flex items-center gap-2">
                  <i class="fa-solid fa-eye text-slate-600"></i>
                  ناظران
                </h3>
                <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                  <i class="fa-solid fa-plus ml-1"></i>
                  افزودن
                </button>
              </div>
              <div class="space-y-2">
                <?php foreach ($watchers as $watcher): ?>
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <img src="<?= $watcher['user']['avatar'] ?>" alt="" class="w-9 h-9 rounded-full">
                  <div class="flex-1 min-w-0">
                    <p class="text-text-primary text-sm font-medium truncate"><?= $watcher['user']['name'] ?></p>
                    <p class="text-text-muted text-xs truncate"><?= $watcher['watch_reason'] ?></p>
                  </div>
                  <span class="w-2 h-2 rounded-full <?= $watcher['watch_status'] === 'open' ? 'bg-green-500' : 'bg-slate-400' ?>"></span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- Workflow Info -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-5">
              <h3 class="text-base font-bold text-text-primary mb-4 flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-slate-600"></i>
                اطلاعات فرایند
              </h3>
              
              <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                  <span class="text-text-muted text-sm">نام فرایند</span>
                  <span class="text-text-primary text-sm font-medium"><?= $workflow['name'] ?></span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                  <span class="text-text-muted text-sm">دپارتمان</span>
                  <span class="text-text-primary text-sm font-medium"><?= $workflow['department'] ?></span>
                </div>
                <div class="flex items-center justify-between py-2">
                  <span class="text-text-muted text-sm">مالک فرایند</span>
                  <span class="text-text-primary text-sm font-medium"><?= $workflow['owner'] ?></span>
                </div>
              </div>
            </div>
            
          </div>
          
        </div>
        
      </div>
    </main>
  </div>

  <!-- Follow-up Modal -->
  <div id="followUpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-text-primary">ثبت یادداشت و پیگیری</h2>
          <button onclick="closeFollowUpModal()" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
        
        <!-- Follow-up Content -->
        <div class="mb-5">
          <label class="text-text-secondary text-sm font-medium mb-2 block">متن یادداشت</label>
          <textarea id="followUpContent" rows="4" class="w-full border border-border-medium rounded-xl px-4 py-3 text-base text-text-primary resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="یادداشت یا توضیحات خود را بنویسید..."></textarea>
        </div>

        <!-- Next Follow-up Date -->
        <div class="mb-5">
          <label class="text-text-secondary text-sm font-medium mb-2 block">تاریخ پیگیری بعدی</label>
          <div class="relative">
            <input type="text" id="nextFollowUpDate" class="w-full border border-border-medium rounded-xl px-4 py-3 text-base text-text-primary focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="۱۴۰۳/۰۹/۲۷">
            <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
          </div>
        </div>

        <!-- Attach File -->
        <div class="mb-5">
          <label class="text-text-secondary text-sm font-medium mb-2 block">پیوست فایل (اختیاری)</label>
          <label class="border-2 border-dashed border-border-medium rounded-xl p-6 text-center cursor-pointer hover:border-indigo-500 transition-all block">
            <input type="file" id="followUpFile" class="hidden" onchange="handleFileSelect(this)">
            <div id="fileUploadPlaceholder">
              <i class="fa-solid fa-cloud-arrow-up text-text-muted text-2xl mb-2"></i>
              <p class="text-text-muted text-sm">برای آپلود فایل کلیک کنید</p>
            </div>
            <div id="fileUploadSelected" class="hidden">
              <i class="fa-solid fa-file-check text-green-500 text-2xl mb-2"></i>
              <p id="selectedFileName" class="text-text-primary text-sm font-medium"></p>
              <p class="text-text-muted text-xs mt-1">برای تغییر کلیک کنید</p>
            </div>
          </label>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="bg-white border-t border-gray-100 px-6 py-4">
        <div class="grid grid-cols-2 gap-3">
          <button onclick="closeFollowUpModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all">
            انصراف
          </button>
          <button onclick="submitFollowUp()" class="h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-check"></i>
            ثبت یادداشت
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Forward Modal -->
  <div id="forwardModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-text-primary">ارجاع به مرحله بعد</h2>
            <p class="text-text-muted text-sm mt-0.5">
              <?= $currentState['name'] ?>
              <i class="fa-solid fa-arrow-left mx-2 text-xs"></i>
              <?= isset($workflowStates[$currentStateIndex + 1]) ? $workflowStates[$currentStateIndex + 1]['name'] : 'تکمیل' ?>
            </p>
          </div>
          <button onclick="closeForwardModal()" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
            <i class="fa-solid fa-xmark text-slate-600"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
        
        <!-- Forward Note -->
        <div class="mb-5">
          <label class="text-text-secondary text-sm font-medium mb-2 block">توضیحات ارجاع</label>
          <textarea id="forwardNote" rows="3" class="w-full border border-border-medium rounded-xl px-4 py-3 text-base text-text-primary resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="توضیحات لازم برای نفر بعدی..."></textarea>
        </div>

        <!-- Select Assignee -->
        <div class="mb-5">
          <label class="text-text-secondary text-sm font-medium mb-3 block">انتخاب مسئول بعدی</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[300px] overflow-y-auto p-1">
            <?php foreach ($assignableUsers as $user): ?>
            <label class="flex flex-col items-center p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-all has-[:checked]:bg-indigo-600 has-[:checked]:ring-2 has-[:checked]:ring-indigo-300 group relative">
              <input type="radio" name="assignee" value="<?= $user['id'] ?>" class="hidden" <?= $user['is_default'] ? 'checked' : '' ?>>
              <?php if ($user['is_default']): ?>
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-star text-white text-[9px]"></i>
              </span>
              <?php endif; ?>
              <img src="<?= $user['avatar'] ?>" alt="" class="w-14 h-14 rounded-full mb-2 ring-2 ring-transparent group-has-[:checked]:ring-white">
              <span class="text-sm text-center font-medium text-slate-700 group-has-[:checked]:text-white leading-tight"><?= $user['name'] ?></span>
              <span class="text-xs text-center text-slate-500 group-has-[:checked]:text-indigo-100 mt-1"><?= $user['position'] ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="bg-white border-t border-gray-100 px-6 py-4">
        <div class="grid grid-cols-2 gap-3">
          <button onclick="closeForwardModal()" class="h-12 bg-gray-100 hover:bg-gray-200 text-slate-700 rounded-xl font-medium transition-all">
            انصراف
          </button>
          <button onclick="submitForward()" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            ارجاع کار
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  
  <script>
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
      document.body.style.overflow = 'hidden';
    }
    
    function closeFollowUpModal() {
      document.getElementById('followUpModal').classList.add('hidden');
      document.body.style.overflow = '';
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
      document.body.style.overflow = 'hidden';
    }
    
    function closeForwardModal() {
      document.getElementById('forwardModal').classList.add('hidden');
      document.body.style.overflow = '';
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
    
    // Close modals on backdrop click
    document.querySelectorAll('#followUpModal, #forwardModal').forEach(modal => {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.add('hidden');
          document.body.style.overflow = '';
        }
      });
    });
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('#followUpModal, #forwardModal').forEach(modal => {
          modal.classList.add('hidden');
        });
        document.body.style.overflow = '';
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
    #forwardModal > div {
      animation: scaleIn 0.2s ease-out;
    }

    @keyframes scaleIn {
      from {
        transform: scale(0.95);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>

</body>
</html>
