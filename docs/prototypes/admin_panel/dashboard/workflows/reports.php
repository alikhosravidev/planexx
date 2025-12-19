<?php
/**
 * صفحه گزارشات مدیریت وظایف
 * داشبورد جامع برای تحلیل عملکرد فرایندها و وظایف
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'گزارشات';
$currentPage = 'workflow';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'گزارشات'],
];

// ========================================
// داده‌های نمونه برای گزارشات
// ========================================

// آمار کلی وظایف
$overviewStats = [
    [
        'title'    => 'کل وظایف',
        'value'    => '۱,۲۴۸',
        'subValue' => '+۱۲۳ این ماه',
        'icon'     => 'fa-solid fa-tasks',
        'color'    => 'indigo',
        'trend'    => 'up',
    ],
    [
        'title'    => 'وظایف در جریان',
        'value'    => '۱۸۷',
        'subValue' => '۱۵% از کل',
        'icon'     => 'fa-solid fa-spinner',
        'color'    => 'blue',
        'trend'    => 'neutral',
    ],
    [
        'title'    => 'تکمیل شده',
        'value'    => '۹۴۲',
        'subValue' => '۷۵.۵% موفقیت',
        'icon'     => 'fa-solid fa-check-circle',
        'color'    => 'green',
        'trend'    => 'up',
    ],
    [
        'title'    => 'معوق و تاخیری',
        'value'    => '۲۳',
        'subValue' => '-۸ نسبت به ماه قبل',
        'icon'     => 'fa-solid fa-exclamation-triangle',
        'color'    => 'red',
        'trend'    => 'down',
    ],
];

// نرخ تکمیل ماهانه (۶ ماه اخیر)
$monthlyCompletionRate = [
    ['month' => 'مهر', 'completed' => 142, 'total' => 168, 'rate' => 84.5],
    ['month' => 'آبان', 'completed' => 156, 'total' => 182, 'rate' => 85.7],
    ['month' => 'آذر', 'completed' => 178, 'total' => 201, 'rate' => 88.6],
    ['month' => 'دی', 'completed' => 134, 'total' => 159, 'rate' => 84.3],
    ['month' => 'بهمن', 'completed' => 189, 'total' => 215, 'rate' => 87.9],
    ['month' => 'اسفند', 'completed' => 143, 'total' => 165, 'rate' => 86.7],
];

// توزیع وظایف بر اساس اولویت
$tasksByPriority = [
    ['priority' => 'فوری', 'count' => 23, 'color' => 'red', 'icon' => 'fa-solid fa-fire'],
    ['priority' => 'بالا', 'count' => 67, 'color' => 'orange', 'icon' => 'fa-solid fa-arrow-up'],
    ['priority' => 'متوسط', 'count' => 156, 'color' => 'yellow', 'icon' => 'fa-solid fa-minus'],
    ['priority' => 'پایین', 'count' => 89, 'color' => 'green', 'icon' => 'fa-solid fa-arrow-down'],
];

// توزیع وظایف بر اساس فرایند
$tasksByWorkflow = [
    ['name' => 'اینسپکشن', 'active' => 45, 'completed' => 312, 'failed' => 8, 'total' => 365],
    ['name' => 'مسیریابی', 'active' => 38, 'completed' => 245, 'failed' => 12, 'total' => 295],
    ['name' => 'سبد رها شده', 'active' => 52, 'completed' => 189, 'failed' => 23, 'total' => 264],
    ['name' => 'استخدام', 'active' => 28, 'completed' => 156, 'failed' => 15, 'total' => 199],
    ['name' => 'درخواست مرخصی', 'active' => 12, 'completed' => 87, 'failed' => 3, 'total' => 102],
];

// عملکرد کارشناسان (بهترین‌ها)
$topPerformers = [
    ['name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200', 'department' => 'فروش', 'completed' => 89, 'avgTime' => '۱.۲ روز', 'rating' => 4.8],
    ['name' => 'مریم رضایی', 'avatar' => 'https://picsum.photos/seed/user2/200/200', 'department' => 'مالی', 'completed' => 76, 'avgTime' => '۱.۵ روز', 'rating' => 4.6],
    ['name' => 'سارا محمدی', 'avatar' => 'https://picsum.photos/seed/user3/200/200', 'department' => 'منابع انسانی', 'completed' => 72, 'avgTime' => '۱.۸ روز', 'rating' => 4.5],
    ['name' => 'رضا کریمی', 'avatar' => 'https://picsum.photos/seed/user4/200/200', 'department' => 'فنی', 'completed' => 68, 'avgTime' => '۲.۱ روز', 'rating' => 4.3],
    ['name' => 'فاطمه نوری', 'avatar' => 'https://picsum.photos/seed/user5/200/200', 'department' => 'فروش', 'completed' => 64, 'avgTime' => '۱.۴ روز', 'rating' => 4.7],
];

// توزیع وظایف بر اساس وضعیت (State Position)
$tasksByStatePosition = [
    ['position' => 'شروع', 'label' => 'start', 'count' => 34, 'color' => 'slate'],
    ['position' => 'در حال انجام', 'label' => 'middle', 'count' => 153, 'color' => 'blue'],
    ['position' => 'موفق', 'label' => 'final-success', 'count' => 892, 'color' => 'green'],
    ['position' => 'ناموفق', 'label' => 'final-failed', 'count' => 45, 'color' => 'red'],
    ['position' => 'بسته شده', 'label' => 'final-closed', 'count' => 124, 'color' => 'gray'],
];

// میانگین زمان تکمیل بر اساس فرایند
$avgCompletionTime = [
    ['workflow' => 'اینسپکشن', 'avgDays' => 3.2, 'target' => 4, 'status' => 'good'],
    ['workflow' => 'مسیریابی', 'avgDays' => 2.8, 'target' => 3, 'status' => 'good'],
    ['workflow' => 'سبد رها شده', 'avgDays' => 1.5, 'target' => 2, 'status' => 'good'],
    ['workflow' => 'استخدام', 'avgDays' => 12.5, 'target' => 10, 'status' => 'warning'],
    ['workflow' => 'درخواست مرخصی', 'avgDays' => 0.8, 'target' => 1, 'status' => 'good'],
];

// فعالیت‌های پیگیری اخیر
$recentFollowUps = [
    ['type' => 'state_transition', 'task' => 'INS-2024-001', 'user' => 'علی احمدی', 'from' => 'مشاوره خصوصی', 'to' => 'منتظر پرداخت', 'time' => '۲ ساعت پیش'],
    ['type' => 'follow_up', 'task' => 'REC-2024-015', 'user' => 'سارا محمدی', 'content' => 'تماس با متقاضی برقرار شد', 'time' => '۳ ساعت پیش'],
    ['type' => 'user_action', 'task' => 'ABN-2024-042', 'user' => 'مریم رضایی', 'content' => 'ارسال پیامک یادآوری', 'time' => '۵ ساعت پیش'],
    ['type' => 'watcher_review', 'task' => 'INS-2024-003', 'user' => 'حسین موسوی', 'content' => 'بررسی و تایید', 'time' => '۶ ساعت پیش'],
    ['type' => 'state_transition', 'task' => 'ROU-2024-008', 'user' => 'رضا کریمی', 'from' => 'ارسال آفر', 'to' => 'در حال مذاکره', 'time' => '۸ ساعت پیش'],
];

// وظایف معوق (نیازمند توجه فوری)
$overdueTasks = [
    ['slug' => 'INS-2024-012', 'title' => 'مشاوره شرکت بتا', 'workflow' => 'اینسپکشن', 'assignee' => 'علی احمدی', 'dueDate' => '۱۵ آذر', 'overdueDays' => 5],
    ['slug' => 'REC-2024-008', 'title' => 'استخدام توسعه‌دهنده ارشد', 'workflow' => 'استخدام', 'assignee' => 'سارا محمدی', 'dueDate' => '۱۸ آذر', 'overdueDays' => 2],
    ['slug' => 'ABN-2024-056', 'title' => 'سبد آقای موسوی', 'workflow' => 'سبد رها شده', 'assignee' => 'فاطمه نوری', 'dueDate' => '۱۹ آذر', 'overdueDays' => 1],
];

// توزیع بار کاری تیم
$teamWorkload = [
    ['name' => 'علی احمدی', 'avatar' => 'https://picsum.photos/seed/user1/200/200', 'active' => 12, 'capacity' => 15, 'utilization' => 80],
    ['name' => 'مریم رضایی', 'avatar' => 'https://picsum.photos/seed/user2/200/200', 'active' => 8, 'capacity' => 12, 'utilization' => 67],
    ['name' => 'سارا محمدی', 'avatar' => 'https://picsum.photos/seed/user3/200/200', 'active' => 14, 'capacity' => 15, 'utilization' => 93],
    ['name' => 'رضا کریمی', 'avatar' => 'https://picsum.photos/seed/user4/200/200', 'active' => 6, 'capacity' => 10, 'utilization' => 60],
    ['name' => 'فاطمه نوری', 'avatar' => 'https://picsum.photos/seed/user5/200/200', 'active' => 11, 'capacity' => 12, 'utilization' => 92],
];

// روند وظایف هفتگی (۴ هفته اخیر)
$weeklyTrend = [
    ['week' => 'هفته اول', 'created' => 45, 'completed' => 38, 'net' => 7],
    ['week' => 'هفته دوم', 'created' => 52, 'completed' => 48, 'net' => 4],
    ['week' => 'هفته سوم', 'created' => 41, 'completed' => 45, 'net' => -4],
    ['week' => 'هفته چهارم', 'created' => 56, 'completed' => 51, 'net' => 5],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Module Header -->
      <?php component('module-header', [
          'pageTitle'   => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Page Title & Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-text-primary leading-snug">گزارشات و تحلیل عملکرد</h1>
            <p class="text-sm text-text-secondary mt-1 leading-normal">نمای کلی از وضعیت فرایندها و عملکرد تیم</p>
          </div>
          
          <!-- Date Filter -->
          <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-bg-primary border border-border-light rounded-xl px-4 py-2.5">
              <i class="fa-solid fa-calendar text-text-muted"></i>
              <select class="bg-transparent text-sm text-text-primary outline-none cursor-pointer leading-normal">
                <option>۳۰ روز اخیر</option>
                <option>۷ روز اخیر</option>
                <option>۹۰ روز اخیر</option>
                <option>امسال</option>
                <option>سفارشی</option>
              </select>
            </div>
            <button class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors leading-normal">
              <i class="fa-solid fa-download"></i>
              <span>خروجی اکسل</span>
            </button>
          </div>
        </div>
        
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
          <?php foreach ($overviewStats as $stat): ?>
            <?php
            $colorClasses = [
                'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200'],
                'blue'   => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
                'green'  => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
                'red'    => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
            ];
              $colors    = $colorClasses[$stat['color']] ?? $colorClasses['indigo'];
              $trendIcon = $stat['trend'] === 'up' ? 'fa-arrow-up text-green-500' : ($stat['trend'] === 'down' ? 'fa-arrow-down text-red-500' : 'fa-minus text-gray-400');
              ?>
            
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:<?= $colors['border'] ?> transition-all duration-200 group">
              <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 <?= $colors['bg'] ?> rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <i class="<?= $stat['icon'] ?> text-xl <?= $colors['text'] ?>"></i>
                </div>
                <i class="fa-solid <?= $trendIcon ?> text-sm"></i>
              </div>
              <div class="text-3xl font-bold text-text-primary leading-tight mb-1"><?= $stat['value'] ?></div>
              <div class="text-sm text-text-secondary leading-normal"><?= $stat['title'] ?></div>
              <div class="text-xs text-text-muted mt-2 leading-normal"><?= $stat['subValue'] ?></div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Main Reports Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          
          <!-- نرخ تکمیل ماهانه -->
          <div class="lg:col-span-2 bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-text-primary leading-snug">نرخ تکمیل ماهانه</h3>
                <p class="text-xs text-text-muted mt-1 leading-normal">درصد موفقیت در تکمیل وظایف</p>
              </div>
              <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5">
                  <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                  <span class="text-text-secondary">تکمیل شده</span>
                </span>
                <span class="flex items-center gap-1.5">
                  <span class="w-3 h-3 bg-gray-200 rounded-full"></span>
                  <span class="text-text-secondary">کل</span>
                </span>
              </div>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <?php foreach ($monthlyCompletionRate as $month): ?>
                  <div class="flex items-center gap-4">
                    <span class="w-16 text-sm text-text-secondary leading-normal"><?= $month['month'] ?></span>
                    <div class="flex-1 h-8 bg-gray-100 rounded-lg overflow-hidden relative">
                      <div class="h-full bg-gradient-to-l from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-end px-3" style="width: <?= $month['rate'] ?>%">
                        <span class="text-xs text-white font-medium"><?= $month['rate'] ?>%</span>
                      </div>
                    </div>
                    <span class="w-20 text-sm text-text-muted text-left leading-normal"><?= $month['completed'] ?>/<?= $month['total'] ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
          <!-- توزیع بر اساس اولویت -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light">
              <h3 class="text-lg font-semibold text-text-primary leading-snug">توزیع بر اساس اولویت</h3>
              <p class="text-xs text-text-muted mt-1 leading-normal">وظایف فعال به تفکیک اولویت</p>
            </div>
            <div class="p-6">
              <?php
                $totalPriority = array_sum(array_column($tasksByPriority, 'count'));
?>
              <div class="space-y-4">
                <?php foreach ($tasksByPriority as $priority):
                    $percentage   = round(($priority['count'] / $totalPriority) * 100, 1);
                    $colorClasses = [
                        'red'    => 'from-red-500 to-red-600',
                        'orange' => 'from-orange-500 to-orange-600',
                        'yellow' => 'from-yellow-500 to-yellow-600',
                        'green'  => 'from-green-500 to-green-600',
                    ];
                    $barColor = $colorClasses[$priority['color']] ?? $colorClasses['green'];
                    ?>
                  <div>
                    <div class="flex items-center justify-between mb-2">
                      <span class="flex items-center gap-2 text-sm text-text-primary font-medium leading-normal">
                        <i class="<?= $priority['icon'] ?> text-xs text-<?= $priority['color'] ?>-500"></i>
                        <?= $priority['priority'] ?>
                      </span>
                      <span class="text-sm text-text-secondary leading-normal"><?= $priority['count'] ?> مورد</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                      <div class="h-full bg-gradient-to-l <?= $barColor ?> rounded-full transition-all duration-500" style="width: <?= $percentage ?>%"></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              
              <!-- Pie Chart Visual -->
              <div class="mt-6 flex items-center justify-center">
                <div class="relative w-32 h-32">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <?php
                        $offset = 0;
$colors                         = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
$i                              = 0;

foreach ($tasksByPriority as $priority):
    $percentage = ($priority['count'] / $totalPriority) * 100;
    $dashArray  = $percentage . ' ' . (100 - $percentage);
    ?>
                      <circle cx="18" cy="18" r="15.9" fill="none" stroke="<?= $colors[$i] ?>" stroke-width="3" 
                              stroke-dasharray="<?= $dashArray ?>" stroke-dashoffset="-<?= $offset ?>" />
                    <?php
      $offset += $percentage;
    $i++;
endforeach;
?>
                  </svg>
                  <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-xl font-bold text-text-primary"><?= $totalPriority ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Second Row Reports -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          
          <!-- توزیع وظایف بر اساس فرایند -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-text-primary leading-snug">وظایف به تفکیک فرایند</h3>
                <p class="text-xs text-text-muted mt-1 leading-normal">توزیع وظایف در هر فرایند</p>
              </div>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <?php foreach ($tasksByWorkflow as $workflow):
                    $successRate = round(($workflow['completed'] / $workflow['total']) * 100, 1);
                    ?>
                  <div class="p-4 bg-bg-secondary rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                      <h4 class="text-base font-medium text-text-primary leading-normal"><?= $workflow['name'] ?></h4>
                      <span class="text-sm text-text-muted leading-normal">نرخ موفقیت: <?= $successRate ?>%</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                      <span class="flex items-center gap-1.5 text-blue-600">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        فعال: <?= $workflow['active'] ?>
                      </span>
                      <span class="flex items-center gap-1.5 text-green-600">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        موفق: <?= $workflow['completed'] ?>
                      </span>
                      <span class="flex items-center gap-1.5 text-red-600">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        ناموفق: <?= $workflow['failed'] ?>
                      </span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden flex">
                      <div class="h-full bg-green-500" style="width: <?= ($workflow['completed'] / $workflow['total']) * 100 ?>%"></div>
                      <div class="h-full bg-blue-500" style="width: <?= ($workflow['active'] / $workflow['total'])     * 100 ?>%"></div>
                      <div class="h-full bg-red-500" style="width: <?= ($workflow['failed'] / $workflow['total'])      * 100 ?>%"></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
          <!-- میانگین زمان تکمیل -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-text-primary leading-snug">میانگین زمان تکمیل</h3>
                <p class="text-xs text-text-muted mt-1 leading-normal">مقایسه با هدف تعیین شده</p>
              </div>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <?php foreach ($avgCompletionTime as $item):
                    $performance = ($item['avgDays'] / $item['target']) * 100;
                    $statusColor = $item['status'] === 'good' ? 'green' : ($item['status'] === 'warning' ? 'yellow' : 'red');
                    ?>
                  <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-bg-secondary transition-colors">
                    <div class="flex-1">
                      <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-text-primary leading-normal"><?= $item['workflow'] ?></span>
                        <div class="flex items-center gap-2">
                          <span class="text-sm text-text-primary font-semibold leading-normal"><?= $item['avgDays'] ?> روز</span>
                          <span class="text-xs text-text-muted leading-normal">/ <?= $item['target'] ?> هدف</span>
                        </div>
                      </div>
                      <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-<?= $statusColor ?>-500 rounded-full transition-all duration-500" 
                             style="width: <?= min($performance, 100) ?>%"></div>
                      </div>
                    </div>
                    <?php if ($item['status'] === 'good'): ?>
                      <i class="fa-solid fa-check-circle text-green-500"></i>
                    <?php else: ?>
                      <i class="fa-solid fa-exclamation-circle text-yellow-500"></i>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Third Row - Team Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          
          <!-- بهترین عملکردها -->
          <div class="lg:col-span-2 bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-text-primary leading-snug">عملکرد برتر کارشناسان</h3>
                <p class="text-xs text-text-muted mt-1 leading-normal">بر اساس تعداد وظایف تکمیل شده</p>
              </div>
              <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium leading-normal">
                مشاهده همه
                <i class="fa-solid fa-arrow-left mr-1"></i>
              </a>
            </div>
            <div class="divide-y divide-border-light">
              <?php foreach ($topPerformers as $index => $performer): ?>
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-bg-secondary transition-colors">
                  <!-- Rank -->
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                    <?php if ($index === 0): ?>bg-yellow-100 text-yellow-700
                    <?php elseif ($index === 1): ?>bg-gray-100 text-gray-600
                    <?php elseif ($index === 2): ?>bg-orange-100 text-orange-700
                    <?php else: ?>bg-bg-secondary text-text-muted
                    <?php endif; ?>">
                    <?= $index + 1 ?>
                  </div>
                  
                  <!-- Avatar & Name -->
                  <div class="flex items-center gap-3 flex-1">
                    <img src="<?= $performer['avatar'] ?>" alt="<?= $performer['name'] ?>" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                    <div>
                      <h4 class="text-sm font-medium text-text-primary leading-normal"><?= $performer['name'] ?></h4>
                      <p class="text-xs text-text-muted leading-normal"><?= $performer['department'] ?></p>
                    </div>
                  </div>
                  
                  <!-- Stats -->
                  <div class="flex items-center gap-6 text-sm">
                    <div class="text-center">
                      <div class="font-semibold text-text-primary"><?= $performer['completed'] ?></div>
                      <div class="text-xs text-text-muted">تکمیل شده</div>
                    </div>
                    <div class="text-center">
                      <div class="font-semibold text-text-primary"><?= $performer['avgTime'] ?></div>
                      <div class="text-xs text-text-muted">میانگین زمان</div>
                    </div>
                    <div class="flex items-center gap-1 text-yellow-500">
                      <i class="fa-solid fa-star text-xs"></i>
                      <span class="font-semibold"><?= $performer['rating'] ?></span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          
          <!-- توزیع وضعیت -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light">
              <h3 class="text-lg font-semibold text-text-primary leading-snug">توزیع وضعیت وظایف</h3>
              <p class="text-xs text-text-muted mt-1 leading-normal">بر اساس موقعیت در فرایند</p>
            </div>
            <div class="p-6">
              <?php
              $totalStates = array_sum(array_column($tasksByStatePosition, 'count'));
?>
              <div class="space-y-3">
                <?php foreach ($tasksByStatePosition as $state):
                    $percentage   = round(($state['count'] / $totalStates) * 100, 1);
                    $colorClasses = [
                        'slate' => 'bg-slate-500',
                        'blue'  => 'bg-blue-500',
                        'green' => 'bg-green-500',
                        'red'   => 'bg-red-500',
                        'gray'  => 'bg-gray-500',
                    ];
                    $bgColor = $colorClasses[$state['color']] ?? 'bg-gray-500';
                    ?>
                  <div class="flex items-center gap-3">
                    <span class="w-3 h-3 <?= $bgColor ?> rounded-full flex-shrink-0"></span>
                    <span class="flex-1 text-sm text-text-primary leading-normal"><?= $state['position'] ?></span>
                    <span class="text-sm font-medium text-text-primary leading-normal"><?= $state['count'] ?></span>
                    <span class="text-xs text-text-muted w-12 text-left leading-normal"><?= $percentage ?>%</span>
                  </div>
                <?php endforeach; ?>
              </div>
              
              <!-- Summary -->
              <div class="mt-6 pt-4 border-t border-border-light">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-text-secondary leading-normal">مجموع</span>
                  <span class="text-lg font-bold text-text-primary leading-normal"><?= number_format($totalStates) ?></span>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Fourth Row - Workload & Trends -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          
          <!-- بار کاری تیم -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light">
              <h3 class="text-lg font-semibold text-text-primary leading-snug">بار کاری تیم</h3>
              <p class="text-xs text-text-muted mt-1 leading-normal">درصد استفاده از ظرفیت هر کارشناس</p>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <?php foreach ($teamWorkload as $member):
                    $utilizationColor = $member['utilization'] >= 90 ? 'red' : ($member['utilization'] >= 70 ? 'yellow' : 'green');
                    ?>
                  <div class="flex items-center gap-4">
                    <img src="<?= $member['avatar'] ?>" alt="<?= $member['name'] ?>" 
                         class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1">
                      <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-medium text-text-primary leading-normal"><?= $member['name'] ?></span>
                        <span class="text-xs text-text-muted leading-normal"><?= $member['active'] ?> از <?= $member['capacity'] ?></span>
                      </div>
                      <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-<?= $utilizationColor ?>-500 rounded-full transition-all duration-500" 
                             style="width: <?= $member['utilization'] ?>%"></div>
                      </div>
                    </div>
                    <span class="text-sm font-semibold text-<?= $utilizationColor ?>-600 w-12 text-left"><?= $member['utilization'] ?>%</span>
                  </div>
                <?php endforeach; ?>
              </div>
              
              <!-- Legend -->
              <div class="mt-6 pt-4 border-t border-border-light flex items-center justify-center gap-6 text-xs">
                <span class="flex items-center gap-1.5">
                  <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                  <span class="text-text-muted">بهینه (کمتر از ۷۰%)</span>
                </span>
                <span class="flex items-center gap-1.5">
                  <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                  <span class="text-text-muted">بالا (۷۰-۹۰%)</span>
                </span>
                <span class="flex items-center gap-1.5">
                  <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                  <span class="text-text-muted">اضافه بار (+۹۰%)</span>
                </span>
              </div>
            </div>
          </div>
          
          <!-- روند هفتگی -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light">
              <h3 class="text-lg font-semibold text-text-primary leading-snug">روند هفتگی وظایف</h3>
              <p class="text-xs text-text-muted mt-1 leading-normal">مقایسه وظایف ایجاد و تکمیل شده</p>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <?php foreach ($weeklyTrend as $week): ?>
                  <div class="p-4 bg-bg-secondary rounded-xl">
                    <div class="flex items-center justify-between mb-3">
                      <span class="text-sm font-medium text-text-primary leading-normal"><?= $week['week'] ?></span>
                      <span class="text-xs px-2 py-1 rounded-md leading-normal
                        <?= $week['net'] > 0 ? 'bg-red-50 text-red-600' : ($week['net'] < 0 ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-600') ?>">
                        <?= $week['net'] > 0 ? '+' : '' ?><?= $week['net'] ?> خالص
                      </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <div class="flex items-center gap-2 mb-1.5">
                          <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                          <span class="text-xs text-text-muted leading-normal">ایجاد شده</span>
                        </div>
                        <span class="text-lg font-semibold text-text-primary"><?= $week['created'] ?></span>
                      </div>
                      <div>
                        <div class="flex items-center gap-2 mb-1.5">
                          <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                          <span class="text-xs text-text-muted leading-normal">تکمیل شده</span>
                        </div>
                        <span class="text-lg font-semibold text-text-primary"><?= $week['completed'] ?></span>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Fifth Row - Activity & Overdue -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- فعالیت‌های اخیر -->
          <div class="lg:col-span-2 bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-text-primary leading-snug">فعالیت‌های اخیر</h3>
                <p class="text-xs text-text-muted mt-1 leading-normal">آخرین پیگیری‌ها و تغییرات وضعیت</p>
              </div>
              <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium leading-normal">
                مشاهده همه
                <i class="fa-solid fa-arrow-left mr-1"></i>
              </a>
            </div>
            <div class="divide-y divide-border-light">
              <?php foreach ($recentFollowUps as $activity):
                  $typeIcons = [
                      'state_transition' => ['icon' => 'fa-solid fa-arrow-right-arrow-left', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                      'follow_up'        => ['icon' => 'fa-solid fa-comment', 'color' => 'text-green-500', 'bg' => 'bg-green-50'],
                      'user_action'      => ['icon' => 'fa-solid fa-bolt', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
                      'watcher_review'   => ['icon' => 'fa-solid fa-eye', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
                  ];
                  $typeInfo = $typeIcons[$activity['type']] ?? $typeIcons['follow_up'];
                  ?>
                <div class="px-6 py-4 flex items-start gap-4 hover:bg-bg-secondary transition-colors">
                  <div class="w-10 h-10 <?= $typeInfo['bg'] ?> rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="<?= $typeInfo['icon'] ?> <?= $typeInfo['color'] ?>"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                      <div>
                        <span class="text-sm font-medium text-text-primary leading-normal"><?= $activity['user'] ?></span>
                        <?php if ($activity['type'] === 'state_transition'): ?>
                          <span class="text-sm text-text-secondary leading-normal">
                            وضعیت را از <span class="font-medium"><?= $activity['from'] ?></span> 
                            به <span class="font-medium"><?= $activity['to'] ?></span> تغییر داد
                          </span>
                        <?php else: ?>
                          <span class="text-sm text-text-secondary leading-normal">: <?= $activity['content'] ?></span>
                        <?php endif; ?>
                      </div>
                      <span class="text-xs text-text-muted whitespace-nowrap leading-normal"><?= $activity['time'] ?></span>
                    </div>
                    <div class="mt-1.5">
                      <span class="inline-flex items-center gap-1.5 text-xs text-indigo-600 leading-normal">
                        <i class="fa-solid fa-hashtag"></i>
                        <?= $activity['task'] ?>
                      </span>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          
          <!-- وظایف معوق -->
          <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light bg-red-50">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-exclamation-triangle text-red-500"></i>
                <h3 class="text-lg font-semibold text-red-700 leading-snug">وظایف معوق</h3>
              </div>
              <p class="text-xs text-red-600 mt-1 leading-normal">نیازمند توجه فوری</p>
            </div>
            <div class="divide-y divide-border-light">
              <?php foreach ($overdueTasks as $task): ?>
                <div class="px-6 py-4 hover:bg-red-50/50 transition-colors">
                  <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="text-xs font-medium text-red-600 leading-normal"><?= $task['slug'] ?></span>
                    <span class="text-xs text-red-500 leading-normal"><?= $task['overdueDays'] ?> روز تاخیر</span>
                  </div>
                  <h4 class="text-sm font-medium text-text-primary mb-2 leading-normal"><?= $task['title'] ?></h4>
                  <div class="flex items-center gap-3 text-xs text-text-muted">
                    <span class="flex items-center gap-1">
                      <i class="fa-solid fa-diagram-project"></i>
                      <?= $task['workflow'] ?>
                    </span>
                    <span class="flex items-center gap-1">
                      <i class="fa-solid fa-user"></i>
                      <?= $task['assignee'] ?>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="px-6 py-4 border-t border-border-light bg-bg-secondary">
              <a href="/dashboard/workflows/tasks.php?filter=overdue" class="flex items-center justify-center gap-2 text-sm text-red-600 font-medium hover:text-red-700 transition-colors leading-normal">
                مشاهده همه وظایف معوق
                <i class="fa-solid fa-arrow-left"></i>
              </a>
            </div>
          </div>
          
        </div>
        
      </div>
      
    </main>
    
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
</body>
</html>
