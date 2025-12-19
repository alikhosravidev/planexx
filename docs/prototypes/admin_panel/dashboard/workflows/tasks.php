<?php
/**
 * صفحه کارها و وظایف
 * نمایش لیست کارها با امکان افزودن، جستجو و فیلتر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'کارها و وظایف';
$currentPage = 'workflow';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'افزودن کار جدید', 'url' => '#', 'icon' => 'fa-solid fa-plus', 'type' => 'primary', 'onclick' => 'openCreateTaskModal()'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'کارها و وظایف'],
];

// فرایندها (برای فیلتر و انتخاب)
$workflows = [
    [
        'id'         => 1,
        'name'       => 'اینسپکشن',
        'slug'       => 'inception',
        'department' => 'فروش',
        'states'     => [
            ['id' => 1, 'name' => 'آزمون ورودی', 'color' => '#E0F2F1', 'position' => 'start', 'default_assignee_id' => null],
            ['id' => 2, 'name' => 'مشاوره خصوصی', 'color' => '#E3F2FD', 'position' => 'middle', 'default_assignee_id' => 1],
            ['id' => 3, 'name' => 'منتظر پرداخت', 'color' => '#FFF3E0', 'position' => 'middle', 'default_assignee_id' => 2],
            ['id' => 4, 'name' => 'پیش پرداخت شده', 'color' => '#E8F5E9', 'position' => 'middle', 'default_assignee_id' => null],
            ['id' => 5, 'name' => 'تکمیل شده', 'color' => '#C8E6C9', 'position' => 'final-success', 'default_assignee_id' => null],
        ],
    ],
    [
        'id'         => 2,
        'name'       => 'مسیریابی',
        'slug'       => 'router-campaign',
        'department' => 'عمومی',
        'states'     => [
            ['id' => 6, 'name' => 'سرنخ', 'color' => '#E0F2F1', 'position' => 'start', 'default_assignee_id' => 3],
            ['id' => 7, 'name' => 'ارسال آفر', 'color' => '#FFF8E1', 'position' => 'middle', 'default_assignee_id' => null],
            ['id' => 8, 'name' => 'در حال مذاکره', 'color' => '#E3F2FD', 'position' => 'middle', 'default_assignee_id' => 4],
            ['id' => 9, 'name' => 'موفق', 'color' => '#C8E6C9', 'position' => 'final-success', 'default_assignee_id' => null],
        ],
    ],
    [
        'id'         => 3,
        'name'       => 'سبد رها شده',
        'slug'       => 'abandoned-cart',
        'department' => 'فروش',
        'states'     => [
            ['id' => 10, 'name' => 'سبد پرداخت نشده', 'color' => '#FFEBEE', 'position' => 'start', 'default_assignee_id' => 5],
            ['id' => 11, 'name' => 'در حال پیگیری', 'color' => '#E3F2FD', 'position' => 'middle', 'default_assignee_id' => null],
            ['id' => 12, 'name' => 'ارسال آفر', 'color' => '#FFF8E1', 'position' => 'middle', 'default_assignee_id' => 2],
            ['id' => 13, 'name' => 'خرید شد', 'color' => '#C8E6C9', 'position' => 'final-success', 'default_assignee_id' => null],
        ],
    ],
    [
        'id'         => 4,
        'name'       => 'استخدام',
        'slug'       => 'recruitment',
        'department' => 'منابع انسانی',
        'states'     => [
            ['id' => 14, 'name' => 'دریافت رزومه', 'color' => '#E3F2FD', 'position' => 'start', 'default_assignee_id' => 6],
            ['id' => 15, 'name' => 'بررسی اولیه', 'color' => '#FFF3E0', 'position' => 'middle', 'default_assignee_id' => null],
            ['id' => 16, 'name' => 'مصاحبه', 'color' => '#F3E5F5', 'position' => 'middle', 'default_assignee_id' => 7],
            ['id' => 17, 'name' => 'استخدام شده', 'color' => '#C8E6C9', 'position' => 'final-success', 'default_assignee_id' => null],
        ],
    ],
];

// کاربران قابل ارجاع
$users = [
    ['id' => 1, 'name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200', 'department' => 'فروش', 'position' => 'کارشناس فروش'],
    ['id' => 2, 'name' => 'مریم رضایی', 'avatar' => 'https://picsum.photos/seed/user2/200/200', 'department' => 'مالی', 'position' => 'حسابدار'],
    ['id' => 3, 'name' => 'سارا محمدی', 'avatar' => 'https://picsum.photos/seed/user3/200/200', 'department' => 'منابع انسانی', 'position' => 'کارشناس HR'],
    ['id' => 4, 'name' => 'رضا کریمی', 'avatar' => 'https://picsum.photos/seed/user4/200/200', 'department' => 'فنی', 'position' => 'توسعه‌دهنده'],
    ['id' => 5, 'name' => 'فاطمه نوری', 'avatar' => 'https://picsum.photos/seed/user5/200/200', 'department' => 'فروش', 'position' => 'مدیر فروش'],
    ['id' => 6, 'name' => 'حسین موسوی', 'avatar' => 'https://picsum.photos/seed/user6/200/200', 'department' => 'منابع انسانی', 'position' => 'مدیر HR'],
    ['id' => 7, 'name' => 'زهرا کاظمی', 'avatar' => 'https://picsum.photos/seed/user7/200/200', 'department' => 'عمومی', 'position' => 'مدیر عملیات'],
    ['id' => 8, 'name' => 'امیر حسینی', 'avatar' => 'https://picsum.photos/seed/user8/200/200', 'department' => 'مالی', 'position' => 'مدیر مالی'],
];

// لیست کارها (مرتب شده بر اساس ددلاین - نزدیک‌ترین اول)
$tasks = [
    [
        'id'                => 1,
        'slug'              => 'INS-2024-001',
        'title'             => 'مشاوره آقای محمدی - شرکت آلفا',
        'description'       => 'جلسه مشاوره اولیه با مدیرعامل شرکت آلفا برای بررسی نیازمندی‌ها',
        'workflow'          => ['id' => 1, 'name' => 'اینسپکشن', 'department' => 'فروش'],
        'current_state'     => ['id' => 2, 'name' => 'مشاوره خصوصی', 'color' => '#E3F2FD', 'order' => 2],
        'total_states'      => 5,
        'assignee'          => ['id' => 1, 'name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200'],
        'created_by'        => ['id' => 5, 'name' => 'فاطمه نوری'],
        'priority'          => 'urgent',
        'due_date'          => '۱۴۰۳/۰۹/۲۷',
        'due_date_raw'      => '2024-12-17',
        'remaining_days'    => 0,
        'follow_ups_count'  => 3,
        'attachments_count' => 2,
        'created_at'        => '۱۴۰۳/۰۹/۲۰',
    ],
    [
        'id'                => 2,
        'slug'              => 'INS-2024-002',
        'title'             => 'آزمون ورودی سارا کریمی',
        'description'       => 'برگزاری آزمون ورودی برای ارزیابی اولیه متقاضی',
        'workflow'          => ['id' => 1, 'name' => 'اینسپکشن', 'department' => 'فروش'],
        'current_state'     => ['id' => 1, 'name' => 'آزمون ورودی', 'color' => '#E0F2F1', 'order' => 1],
        'total_states'      => 5,
        'assignee'          => ['id' => 3, 'name' => 'سارا محمدی', 'avatar' => 'https://picsum.photos/seed/user3/200/200'],
        'created_by'        => ['id' => 1, 'name' => 'علی احمدی'],
        'priority'          => 'high',
        'due_date'          => '۱۴۰۳/۰۹/۲۸',
        'due_date_raw'      => '2024-12-18',
        'remaining_days'    => 1,
        'follow_ups_count'  => 1,
        'attachments_count' => 0,
        'created_at'        => '۱۴۰۳/۰۹/۲۲',
    ],
    [
        'id'                => 3,
        'slug'              => 'RTE-2024-015',
        'title'             => 'سرنخ از کمپین گوگل - شرکت بتا',
        'description'       => 'پیگیری سرنخ دریافتی از کمپین تبلیغاتی گوگل',
        'workflow'          => ['id' => 2, 'name' => 'مسیریابی', 'department' => 'عمومی'],
        'current_state'     => ['id' => 7, 'name' => 'ارسال آفر', 'color' => '#FFF8E1', 'order' => 2],
        'total_states'      => 4,
        'assignee'          => ['id' => 7, 'name' => 'زهرا کاظمی', 'avatar' => 'https://picsum.photos/seed/user7/200/200'],
        'created_by'        => ['id' => 3, 'name' => 'سارا محمدی'],
        'priority'          => 'medium',
        'due_date'          => '۱۴۰۳/۰۹/۲۹',
        'due_date_raw'      => '2024-12-19',
        'remaining_days'    => 2,
        'follow_ups_count'  => 5,
        'attachments_count' => 1,
        'created_at'        => '۱۴۰۳/۰۹/۱۵',
    ],
    [
        'id'                => 4,
        'slug'              => 'ABN-2024-042',
        'title'             => 'پیگیری سبد خرید - سفارش #۱۲۳۴۵',
        'description'       => 'سبد خرید به ارزش ۱۵ میلیون تومان رها شده - نیاز به تماس',
        'workflow'          => ['id' => 3, 'name' => 'سبد رها شده', 'department' => 'فروش'],
        'current_state'     => ['id' => 11, 'name' => 'در حال پیگیری', 'color' => '#E3F2FD', 'order' => 2],
        'total_states'      => 4,
        'assignee'          => ['id' => 1, 'name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200'],
        'created_by'        => ['id' => 5, 'name' => 'فاطمه نوری'],
        'priority'          => 'high',
        'due_date'          => '۱۴۰۳/۰۹/۳۰',
        'due_date_raw'      => '2024-12-20',
        'remaining_days'    => 3,
        'follow_ups_count'  => 2,
        'attachments_count' => 0,
        'created_at'        => '۱۴۰۳/۰۹/۲۵',
    ],
    [
        'id'                => 5,
        'slug'              => 'RCR-2024-008',
        'title'             => 'مصاحبه کارشناس فروش - رضا موسوی',
        'description'       => 'مصاحبه حضوری با متقاضی استخدام برای موقعیت کارشناس فروش',
        'workflow'          => ['id' => 4, 'name' => 'استخدام', 'department' => 'منابع انسانی'],
        'current_state'     => ['id' => 16, 'name' => 'مصاحبه', 'color' => '#F3E5F5', 'order' => 3],
        'total_states'      => 4,
        'assignee'          => ['id' => 6, 'name' => 'حسین موسوی', 'avatar' => 'https://picsum.photos/seed/user6/200/200'],
        'created_by'        => ['id' => 6, 'name' => 'حسین موسوی'],
        'priority'          => 'medium',
        'due_date'          => '۱۴۰۳/۱۰/۰۲',
        'due_date_raw'      => '2024-12-22',
        'remaining_days'    => 5,
        'follow_ups_count'  => 4,
        'attachments_count' => 3,
        'created_at'        => '۱۴۰۳/۰۹/۱۰',
    ],
    [
        'id'                => 6,
        'slug'              => 'INS-2024-003',
        'title'             => 'منتظر پرداخت - پروژه گاما',
        'description'       => 'پیش‌فاکتور ارسال شده، منتظر پرداخت مشتری',
        'workflow'          => ['id' => 1, 'name' => 'اینسپکشن', 'department' => 'فروش'],
        'current_state'     => ['id' => 3, 'name' => 'منتظر پرداخت', 'color' => '#FFF3E0', 'order' => 3],
        'total_states'      => 5,
        'assignee'          => ['id' => 2, 'name' => 'مریم رضایی', 'avatar' => 'https://picsum.photos/seed/user2/200/200'],
        'created_by'        => ['id' => 1, 'name' => 'علی احمدی'],
        'priority'          => 'low',
        'due_date'          => '۱۴۰۳/۱۰/۰۵',
        'due_date_raw'      => '2024-12-25',
        'remaining_days'    => 8,
        'follow_ups_count'  => 2,
        'attachments_count' => 1,
        'created_at'        => '۱۴۰۳/۰۹/۱۸',
    ],
    [
        'id'                => 7,
        'slug'              => 'RTE-2024-016',
        'title'             => 'مذاکره با شرکت دلتا',
        'description'       => 'ادامه مذاکرات قیمت و شرایط قرارداد',
        'workflow'          => ['id' => 2, 'name' => 'مسیریابی', 'department' => 'عمومی'],
        'current_state'     => ['id' => 8, 'name' => 'در حال مذاکره', 'color' => '#E3F2FD', 'order' => 3],
        'total_states'      => 4,
        'assignee'          => ['id' => 4, 'name' => 'رضا کریمی', 'avatar' => 'https://picsum.photos/seed/user4/200/200'],
        'created_by'        => ['id' => 7, 'name' => 'زهرا کاظمی'],
        'priority'          => 'medium',
        'due_date'          => '۱۴۰۳/۱۰/۱۰',
        'due_date_raw'      => '2024-12-30',
        'remaining_days'    => 13,
        'follow_ups_count'  => 6,
        'attachments_count' => 2,
        'created_at'        => '۱۴۰۳/۰۹/۰۵',
    ],
];

// رنگ‌های اولویت
$priorityStyles = [
    'low'    => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'کم', 'dot' => 'bg-slate-400'],
    'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'متوسط', 'dot' => 'bg-blue-500'],
    'high'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'بالا', 'dot' => 'bg-orange-500'],
    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'فوری', 'dot' => 'bg-red-500'],
];

// آمار کلی
$stats = [
    'total'   => count($tasks),
    'urgent'  => count(array_filter($tasks, fn ($t) => $t['priority'] === 'urgent')),
    'overdue' => count(array_filter($tasks, fn ($t) => $t['remaining_days'] < 0)),
    'today'   => count(array_filter($tasks, fn ($t) => $t['remaining_days'] === 0)),
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Filters & Search -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-5 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            
            <!-- Search -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light px-lg py-3 text-sm text-text-secondary flex items-center leading-normal">
                    <i class="fa-solid fa-search"></i>
                  </label>
                  <input type="text" id="searchInput"
                         class="flex-1 px-lg py-3 text-base text-text-primary outline-none bg-transparent leading-normal"
                         placeholder="جستجوی کار...">
                </div>
              </div>
            </div>
            
            <!-- Workflow Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3 text-sm text-text-secondary flex items-center leading-normal">
                    فرایند
                  </label>
                  <select id="workflowFilter" class="flex-1 px-lg py-3 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                    <option value="">همه فرایندها</option>
                    <?php foreach ($workflows as $wf): ?>
                    <option value="<?= $wf['id'] ?>"><?= $wf['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Assignee Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3 text-sm text-text-secondary flex items-center leading-normal">
                    مسئول
                  </label>
                  <select id="assigneeFilter" class="flex-1 px-lg py-3 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                    <option value="">همه افراد</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Priority Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3 text-sm text-text-secondary flex items-center leading-normal">
                    اولویت
                  </label>
                  <select id="priorityFilter" class="flex-1 px-lg py-3 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                    <option value="">همه</option>
                    <option value="urgent">فوری</option>
                    <option value="high">بالا</option>
                    <option value="medium">متوسط</option>
                    <option value="low">کم</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Reset Filters -->
            <div class="md:col-span-1 flex items-center">
              <button type="button" class="text-sm text-text-muted hover:text-primary transition-colors leading-normal" onclick="resetFilters()">
                <i class="fa-solid fa-rotate-right ml-1"></i>
                پاک کردن فیلترها
              </button>
            </div>
            
          </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div class="bg-bg-primary border border-border-light rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-list-check text-indigo-600"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-text-primary"><?= $stats['total'] ?></p>
                <p class="text-xs text-text-muted">کل کارها</p>
              </div>
            </div>
          </div>
          <div class="bg-bg-primary border border-border-light rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-fire text-red-600"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-text-primary"><?= $stats['urgent'] ?></p>
                <p class="text-xs text-text-muted">فوری</p>
              </div>
            </div>
          </div>
          <div class="bg-bg-primary border border-border-light rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-clock text-amber-600"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-text-primary"><?= $stats['today'] ?></p>
                <p class="text-xs text-text-muted">ددلاین امروز</p>
              </div>
            </div>
          </div>
          <div class="bg-bg-primary border border-border-light rounded-xl p-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-text-primary"><?= $stats['overdue'] ?></p>
                <p class="text-xs text-text-muted">عقب‌افتاده</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tasks List -->
        <div class="space-y-3" id="tasksList">
          <?php foreach ($tasks as $task):
              $priority  = $priorityStyles[$task['priority']];
              $progress  = round(($task['current_state']['order'] / $task['total_states']) * 100);
              $isOverdue = $task['remaining_days'] < 0;
              $isToday   = $task['remaining_days'] === 0;
              ?>
          <div class="task-row bg-bg-primary border border-border-light rounded-xl overflow-hidden hover:shadow-md hover:border-indigo-200 transition-all duration-200 cursor-pointer group"
               data-workflow="<?= $task['workflow']['id'] ?>"
               data-assignee="<?= $task['assignee']['id'] ?>"
               data-priority="<?= $task['priority'] ?>"
               data-title="<?= $task['title'] ?>"
               data-slug="<?= $task['slug'] ?>"
               onclick="window.location.href='task-detail.php?id=<?= $task['id'] ?>'">
            
            <div class="p-4 lg:p-5">
              <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                
                <!-- Right Side: Priority Indicator + Main Info -->
                <div class="flex items-start gap-4 flex-1 min-w-0">
                  
                  <!-- Priority Indicator -->
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center <?= $priority['bg'] ?>">
                      <div class="w-3 h-3 rounded-full <?= $priority['dot'] ?>"></div>
                    </div>
                  </div>
                  
                  <!-- Main Info -->
                  <div class="flex-1 min-w-0">
                    <h3 class="text-base font-bold text-text-primary mb-2 truncate group-hover:text-indigo-600 transition-colors">
                      <?= $task['title'] ?>
                    </h3>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-text-muted">
                      <span class="flex items-center gap-1">
                        <i class="fa-solid fa-message"></i>
                        <?= $task['follow_ups_count'] ?> پیگیری
                      </span>
                      <?php if ($task['attachments_count'] > 0): ?>
                      <span class="flex items-center gap-1">
                        <i class="fa-solid fa-paperclip"></i>
                        <?= $task['attachments_count'] ?> پیوست
                      </span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                
                <!-- Center: Current State & Progress -->
                <div class="lg:w-64 flex-shrink-0">
                  <div class="flex items-center gap-2 mb-2">
                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: <?= $task['current_state']['color'] ?>; filter: saturate(1.5) brightness(0.95);"></div>
                    <span class="text-sm font-semibold text-text-primary"><?= $task['current_state']['name'] ?></span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                      <?= $task['current_state']['order'] ?>/<?= $task['total_states'] ?>
                    </span>
                    <?php if ($task['priority'] === 'urgent'): ?>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-red-100 text-red-700 mr-auto">
                      <i class="fa-solid fa-fire text-[10px]"></i> فوری
                    </span>
                    <?php endif; ?>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                      <div class="h-full rounded-full transition-all duration-300" 
                           style="width: <?= $progress ?>%; background-color: <?= $task['current_state']['color'] ?>; filter: saturate(1.5) brightness(0.9);"></div>
                    </div>
                  </div>
                </div>
                
                <!-- Left: Assignee & Deadline -->
                <div class="flex items-center gap-4 lg:gap-6 lg:w-auto">
                  
                  <!-- Assignee -->
                  <div class="flex items-center gap-2">
                    <img src="<?= $task['assignee']['avatar'] ?>" alt="<?= $task['assignee']['name'] ?>" 
                         class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm">
                    <span class="text-sm text-text-secondary hidden lg:inline"><?= $task['assignee']['name'] ?></span>
                  </div>
                  
                  <!-- Deadline -->
                  <div class="flex items-center gap-2 px-3 py-2 rounded-lg <?= $isOverdue ? 'bg-red-50' : ($isToday ? 'bg-amber-50' : 'bg-gray-50') ?>">
                    <i class="fa-solid fa-calendar-day <?= $isOverdue ? 'text-red-500' : ($isToday ? 'text-amber-500' : 'text-text-muted') ?>"></i>
                    <div class="text-left">
                      <p class="text-xs font-medium <?= $isOverdue ? 'text-red-600' : ($isToday ? 'text-amber-600' : 'text-text-primary') ?>">
                        <?= $task['due_date'] ?>
                      </p>
                      <p class="text-[10px] <?= $isOverdue ? 'text-red-500' : ($isToday ? 'text-amber-500' : 'text-text-muted') ?>">
                        <?php if ($isOverdue): ?>
                          <?= abs($task['remaining_days']) ?> روز عقب‌افتاده
                        <?php elseif ($isToday): ?>
                          امروز
                        <?php else: ?>
                          <?= $task['remaining_days'] ?> روز مانده
                        <?php endif; ?>
                      </p>
                    </div>
                  </div>
                  
                  <!-- Arrow -->
                  <div class="hidden lg:flex w-8 h-8 items-center justify-center text-text-muted group-hover:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                  </div>
                  
                </div>
                
              </div>
            </div>
            
          </div>
          <?php endforeach; ?>
          
          <!-- Empty State -->
          <div id="emptyState" class="hidden bg-bg-primary border border-border-light rounded-xl p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-solid fa-inbox text-2xl text-text-muted"></i>
            </div>
            <h3 class="text-lg font-bold text-text-primary mb-2">کاری یافت نشد</h3>
            <p class="text-sm text-text-muted">با تغییر فیلترها یا جستجو، کار مورد نظر را پیدا کنید</p>
          </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6">
          <div class="text-sm text-text-muted">
            نمایش <span class="font-semibold text-text-primary">۱</span> تا <span class="font-semibold text-text-primary">۷</span> از <span class="font-semibold text-text-primary">۷</span> کار
          </div>
          <div class="flex items-center gap-2">
            <button class="w-10 h-10 flex items-center justify-center border border-border-medium rounded-xl text-text-muted hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all disabled:opacity-40 disabled:cursor-not-allowed" disabled>
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button class="min-w-[40px] h-10 px-3 flex items-center justify-center border-2 border-indigo-600 bg-indigo-600 rounded-xl font-semibold text-white transition-all">
              ۱
            </button>
            <button class="min-w-[40px] h-10 px-3 flex items-center justify-center border border-border-medium rounded-xl font-medium text-text-primary hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
              ۲
            </button>
            <button class="min-w-[40px] h-10 px-3 flex items-center justify-center border border-border-medium rounded-xl font-medium text-text-primary hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
              ۳
            </button>
            <span class="text-text-muted px-2">...</span>
            <button class="min-w-[40px] h-10 px-3 flex items-center justify-center border border-border-medium rounded-xl font-medium text-text-primary hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
              ۱۰
            </button>
            <button class="w-10 h-10 flex items-center justify-center border border-border-medium rounded-xl text-text-primary hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
          </div>
        </div>
        
      </div>
    </main>
  </div>
  
  <!-- Create Task Modal -->
  <div id="createTaskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div onclick="event.stopPropagation()" class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl">
      
      <!-- Modal Header -->
      <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-text-primary">افزودن کار جدید</h2>
          <button onclick="closeCreateTaskModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-gray-100 rounded-lg transition-all">
            <i class="fa-solid fa-times"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-5 overflow-y-auto max-h-[calc(90vh-160px)]">
        <form id="createTaskForm">
          
          <!-- Step 1: Select Workflow -->
          <div id="step1" class="space-y-5">
            <div class="text-center mb-6">
              <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-diagram-project text-indigo-600 text-xl"></i>
              </div>
              <h3 class="text-base font-bold text-text-primary">انتخاب فرایند</h3>
              <p class="text-sm text-text-muted mt-1">کار جدید را در کدام فرایند ایجاد می‌کنید؟</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <?php foreach ($workflows as $wf): ?>
              <label class="workflow-option cursor-pointer">
                <input type="radio" name="workflow_id" value="<?= $wf['id'] ?>" class="hidden" data-states='<?= json_encode($wf['states']) ?>'>
                <div class="border-2 border-border-medium rounded-xl p-4 hover:border-indigo-300 transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                      <i class="fa-solid fa-diagram-project text-indigo-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="text-base font-bold text-text-primary"><?= $wf['name'] ?></h4>
                      <p class="text-xs text-text-muted"><?= $wf['department'] ?> • <?= count($wf['states']) ?> مرحله</p>
                    </div>
                  </div>
                </div>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
          
          <!-- Step 2: Task Details (Hidden Initially) -->
          <div id="step2" class="hidden space-y-5">
            
            <!-- Selected Workflow Badge -->
            <div id="selectedWorkflowBadge" class="flex items-center gap-2 px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-xl">
              <i class="fa-solid fa-diagram-project text-indigo-600"></i>
              <span class="text-sm font-medium text-indigo-700" id="selectedWorkflowName"></span>
              <button type="button" onclick="goToStep1()" class="mr-auto text-indigo-600 hover:text-indigo-800 text-sm">
                <i class="fa-solid fa-pen ml-1"></i>
                تغییر
              </button>
            </div>
            
            <!-- Title -->
            <div>
              <label class="block text-sm font-medium text-text-secondary mb-2">عنوان کار <span class="text-red-500">*</span></label>
              <input type="text" name="title" required
                     class="w-full px-4 py-3 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all"
                     placeholder="عنوان کار را وارد کنید...">
            </div>
            
            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-text-secondary mb-2">توضیحات</label>
              <textarea name="description" rows="3"
                        class="w-full px-4 py-3 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all resize-none"
                        placeholder="توضیحات بیشتر درباره کار..."></textarea>
            </div>
            
            <!-- Two Columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              
              <!-- Priority -->
              <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">اولویت</label>
                <select name="priority" class="w-full px-4 py-3 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all cursor-pointer">
                  <option value="medium">متوسط</option>
                  <option value="low">کم</option>
                  <option value="high">بالا</option>
                  <option value="urgent">فوری</option>
                </select>
              </div>
              
              <!-- Due Date -->
              <div>
                <label class="block text-sm font-medium text-text-secondary mb-2">ددلاین</label>
                <input type="date" name="due_date"
                       class="w-full px-4 py-3 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all">
              </div>
              
            </div>
            
            <!-- Assignee -->
            <div>
              <label class="block text-sm font-medium text-text-secondary mb-2">مسئول انجام <span class="text-red-500">*</span></label>
              <div class="relative">
                <input type="text" id="assigneeSearch" 
                       class="w-full px-4 py-3 pr-10 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all"
                       placeholder="جستجو و انتخاب مسئول..."
                       autocomplete="off">
                <i class="fa-solid fa-search absolute right-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
                <input type="hidden" name="assignee_id" id="assigneeId" required>
                
                <!-- Assignee Dropdown -->
                <div id="assigneeDropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-border-medium rounded-xl shadow-lg max-h-60 overflow-y-auto z-10">
                  <?php foreach ($users as $user): ?>
                  <div class="assignee-option flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                       data-id="<?= $user['id'] ?>" data-name="<?= $user['name'] ?>">
                    <img src="<?= $user['avatar'] ?>" alt="<?= $user['name'] ?>" class="w-8 h-8 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-text-primary"><?= $user['name'] ?></p>
                      <p class="text-xs text-text-muted"><?= $user['position'] ?> - <?= $user['department'] ?></p>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <!-- Selected Assignee -->
              <div id="selectedAssignee" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-lg">
                <img id="selectedAssigneeAvatar" src="" alt="" class="w-6 h-6 rounded-full object-cover">
                <span id="selectedAssigneeName" class="text-sm font-medium text-green-700"></span>
                <button type="button" onclick="clearAssignee()" class="mr-auto text-green-600 hover:text-green-800">
                  <i class="fa-solid fa-times"></i>
                </button>
              </div>
              
              <!-- Default Assignee Notice -->
              <div id="defaultAssigneeNotice" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                <i class="fa-solid fa-info-circle text-blue-500"></i>
                <span class="text-xs text-blue-700">مسئول پیش‌فرض این مرحله انتخاب شده است</span>
              </div>
            </div>
            
            <!-- Estimated Hours -->
            <div>
              <label class="block text-sm font-medium text-text-secondary mb-2">زمان تخمینی (ساعت)</label>
              <input type="number" name="estimated_hours" min="0" step="0.5"
                     class="w-full px-4 py-3 border border-border-medium rounded-xl text-base text-text-primary outline-none focus:border-indigo-600 focus:shadow-focus transition-all"
                     placeholder="مثلاً: 2">
            </div>
            
          </div>
          
        </form>
      </div>

      <!-- Modal Footer -->
      <div id="modalFooter" class="hidden sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4">
        <div class="flex items-center gap-3">
          <button type="button" onclick="closeCreateTaskModal()" class="flex-1 px-6 py-3 text-base font-medium text-text-secondary bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
            انصراف
          </button>
          <button type="button" id="submitTaskBtn" onclick="submitTask()" class="flex-1 px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all">
            <i class="fa-solid fa-check ml-2"></i>
            ایجاد کار
          </button>
        </div>
      </div>

    </div>
  </div>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  // Workflows data for JS
  const workflowsData = <?= json_encode($workflows) ?>;
  const usersData = <?= json_encode($users) ?>;
  let selectedWorkflow = null;
  
  // Open Create Task Modal
  function openCreateTaskModal() {
    document.getElementById('createTaskModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    resetCreateTaskForm();
  }
  
  // Close Create Task Modal
  function closeCreateTaskModal() {
    document.getElementById('createTaskModal').classList.add('hidden');
    document.body.style.overflow = '';
  }
  
  // Reset form
  function resetCreateTaskForm() {
    document.getElementById('createTaskForm').reset();
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('modalFooter').classList.add('hidden');
    document.getElementById('selectedAssignee').classList.add('hidden');
    document.getElementById('defaultAssigneeNotice').classList.add('hidden');
    document.querySelectorAll('.workflow-option input').forEach(i => i.checked = false);
    document.querySelectorAll('.workflow-option > div').forEach(d => {
      d.classList.remove('border-indigo-600', 'bg-indigo-50');
    });
    selectedWorkflow = null;
  }
  
  // Workflow selection
  document.querySelectorAll('.workflow-option').forEach(option => {
    option.addEventListener('click', function() {
      const input = this.querySelector('input');
      
      // Update styles
      document.querySelectorAll('.workflow-option > div').forEach(d => {
        d.classList.remove('border-indigo-600', 'bg-indigo-50');
      });
      this.querySelector('div').classList.add('border-indigo-600', 'bg-indigo-50');
      
      // Check the input
      input.checked = true;
      
      // Store selected workflow
      selectedWorkflow = workflowsData.find(w => w.id == input.value);
      
      // Go directly to step 2
      goToStep2();
    });
  });
  
  // Go to Step 2
  function goToStep2() {
    if (!selectedWorkflow) return;
    
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
    document.getElementById('modalFooter').classList.remove('hidden');
    
    // Update selected workflow badge
    document.getElementById('selectedWorkflowName').textContent = selectedWorkflow.name;
    
    // Check for default assignee in first state
    const firstState = selectedWorkflow.states.find(s => s.position === 'start');
    if (firstState && firstState.default_assignee_id) {
      const defaultUser = usersData.find(u => u.id === firstState.default_assignee_id);
      if (defaultUser) {
        selectAssignee(defaultUser.id, defaultUser.name, defaultUser.avatar, true);
      }
    }
  }
  
  // Go back to Step 1
  function goToStep1() {
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('modalFooter').classList.add('hidden');
  }
  
  // Assignee search
  const assigneeSearch = document.getElementById('assigneeSearch');
  const assigneeDropdown = document.getElementById('assigneeDropdown');
  
  assigneeSearch.addEventListener('focus', () => {
    assigneeDropdown.classList.remove('hidden');
  });
  
  assigneeSearch.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('.assignee-option').forEach(opt => {
      const name = opt.dataset.name.toLowerCase();
      opt.style.display = name.includes(query) ? 'flex' : 'none';
    });
  });
  
  document.addEventListener('click', (e) => {
    if (!e.target.closest('#assigneeSearch') && !e.target.closest('#assigneeDropdown')) {
      assigneeDropdown.classList.add('hidden');
    }
  });
  
  document.querySelectorAll('.assignee-option').forEach(opt => {
    opt.addEventListener('click', function() {
      const user = usersData.find(u => u.id == this.dataset.id);
      selectAssignee(this.dataset.id, this.dataset.name, user.avatar, false);
      assigneeDropdown.classList.add('hidden');
    });
  });
  
  function selectAssignee(id, name, avatar, isDefault) {
    document.getElementById('assigneeId').value = id;
    document.getElementById('assigneeSearch').value = '';
    document.getElementById('selectedAssignee').classList.remove('hidden');
    document.getElementById('selectedAssigneeAvatar').src = avatar;
    document.getElementById('selectedAssigneeName').textContent = name;
    
    if (isDefault) {
      document.getElementById('defaultAssigneeNotice').classList.remove('hidden');
    } else {
      document.getElementById('defaultAssigneeNotice').classList.add('hidden');
    }
  }
  
  function clearAssignee() {
    document.getElementById('assigneeId').value = '';
    document.getElementById('selectedAssignee').classList.add('hidden');
    document.getElementById('defaultAssigneeNotice').classList.add('hidden');
  }
  
  // Submit task
  function submitTask() {
    const form = document.getElementById('createTaskForm');
    const title = form.querySelector('[name="title"]').value;
    const assigneeId = document.getElementById('assigneeId').value;
    
    if (!title.trim()) {
      alert('لطفاً عنوان کار را وارد کنید');
      return;
    }
    
    if (!assigneeId) {
      alert('لطفاً مسئول انجام کار را انتخاب کنید');
      return;
    }
    
    // Simulate submission
    alert('کار جدید با موفقیت ایجاد شد');
    closeCreateTaskModal();
    location.reload();
  }
  
  // Close modal on backdrop click
  document.getElementById('createTaskModal').addEventListener('click', function(e) {
    if (e.target === this) closeCreateTaskModal();
  });
  
  // Close with ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCreateTaskModal();
  });
  
  // Filter tasks
  const searchInput = document.getElementById('searchInput');
  const workflowFilter = document.getElementById('workflowFilter');
  const assigneeFilter = document.getElementById('assigneeFilter');
  const priorityFilter = document.getElementById('priorityFilter');
  const taskRows = document.querySelectorAll('.task-row');
  const emptyState = document.getElementById('emptyState');
  
  function filterTasks() {
    const search = searchInput.value.toLowerCase();
    const workflow = workflowFilter.value;
    const assignee = assigneeFilter.value;
    const priority = priorityFilter.value;
    
    let visibleCount = 0;
    
    taskRows.forEach(row => {
      const matchSearch = !search || 
        row.dataset.title.toLowerCase().includes(search) || 
        row.dataset.slug.toLowerCase().includes(search);
      const matchWorkflow = !workflow || row.dataset.workflow === workflow;
      const matchAssignee = !assignee || row.dataset.assignee === assignee;
      const matchPriority = !priority || row.dataset.priority === priority;
      
      if (matchSearch && matchWorkflow && matchAssignee && matchPriority) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    
    emptyState.classList.toggle('hidden', visibleCount > 0);
  }
  
  searchInput.addEventListener('input', filterTasks);
  workflowFilter.addEventListener('change', filterTasks);
  assigneeFilter.addEventListener('change', filterTasks);
  priorityFilter.addEventListener('change', filterTasks);
  
  function resetFilters() {
    searchInput.value = '';
    workflowFilter.value = '';
    assigneeFilter.value = '';
    priorityFilter.value = '';
    filterTasks();
  }
  </script>
  
</body>
</html>
