<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'داشبورد';
$currentPage = 'workflow';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف'],
];

// آمار ماژول
$stats = [
    [
        'title'      => 'فرایندهای فعال',
        'value'      => '۱۲',
        'change'     => '+۲',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-diagram-project',
        'color'      => 'indigo',
    ],
    [
        'title'      => 'کارهای در جریان',
        'value'      => '۱۴۷',
        'change'     => '+۳۸',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-spinner',
        'color'      => 'blue',
    ],
    [
        'title'      => 'کارهای تکمیل شده',
        'value'      => '۸۹۲',
        'change'     => '+۵۶ این ماه',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-check-circle',
        'color'      => 'green',
    ],
    [
        'title'      => 'کارهای معوق',
        'value'      => '۸',
        'change'     => '-۳',
        'changeType' => 'decrease',
        'icon'       => 'fa-solid fa-clock',
        'color'      => 'red',
    ],
];

// منوهای دسترسی سریع
$quickAccessModules = [
    [
        'title'   => 'مدیریت فرایندها',
        'icon'    => 'fa-solid fa-diagram-project',
        'color'   => 'indigo',
        'url'     => '/dashboard/workflows/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'ایجاد فرایند جدید',
        'icon'    => 'fa-solid fa-plus-circle',
        'color'   => 'green',
        'url'     => '/dashboard/workflows/create.php',
        'enabled' => true,
    ],
    [
        'title'   => 'کارهای جاری',
        'icon'    => 'fa-solid fa-list-check',
        'color'   => 'blue',
        'url'     => '/dashboard/workflows/tasks.php',
        'enabled' => true,
    ],
    [
        'title'   => 'گزارش‌گیری',
        'icon'    => 'fa-solid fa-chart-bar',
        'color'   => 'purple',
        'url'     => '#',
        'enabled' => false,
    ],
];

// فرایندهای پرکار
$topWorkflows = [
    [
        'name'         => 'اینسپکشن',
        'slug'         => 'inception',
        'department'   => 'فروش',
        'tasks_count'  => 45,
        'states_count' => 5,
    ],
    [
        'name'         => 'مسیریابی',
        'slug'         => 'router-campaign',
        'department'   => 'عمومی',
        'tasks_count'  => 38,
        'states_count' => 4,
    ],
    [
        'name'         => 'کمپین فیدبک',
        'slug'         => 'feedback-campaign',
        'department'   => 'عمومی',
        'tasks_count'  => 32,
        'states_count' => 4,
    ],
    [
        'name'         => 'سبد رها شده',
        'slug'         => 'abandoned-cart',
        'department'   => 'فروش',
        'tasks_count'  => 28,
        'states_count' => 4,
    ],
];

// آمار کارها به تفکیک وضعیت
$taskStatusStats = [
    ['name' => 'مرحله آغازین', 'count' => 23, 'color' => 'slate'],
    ['name' => 'در حال انجام', 'count' => 67, 'color' => 'blue'],
    ['name' => 'موفق و بسته شده', 'count' => 892, 'color' => 'green'],
    ['name' => 'ناموفق', 'count' => 12, 'color' => 'red'],
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
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
          <?php foreach ($stats as $stat): ?>
            <?php
            $iconColors = [
                'blue'   => 'text-blue-500',
                'green'  => 'text-green-500',
                'yellow' => 'text-yellow-500',
                'purple' => 'text-purple-500',
                'indigo' => 'text-indigo-500',
                'red'    => 'text-red-500',
            ];
              $iconColor = $iconColors[$stat['color']] ?? $iconColors['blue'];
              ?>
            
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-<?= $stat['color'] ?>-200 transition-all duration-200 relative overflow-hidden group">
              <!-- Large Transparent Icon Background -->
              <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-[0.05] group-hover:opacity-[0.12] transition-opacity duration-300">
                <i class="<?= $stat['icon'] ?> text-[108px] <?= $iconColor ?>"></i>
              </div>
              
              <!-- Content -->
              <div class="relative z-10">
                <div class="text-sm text-text-secondary mb-2 font-medium leading-normal"><?= $stat['title'] ?></div>
                <div class="text-4xl font-bold text-text-primary leading-tight"><?= $stat['value'] ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Quick Access -->
        <div class="mb-8">
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($quickAccessModules as $module): ?>
              <?php
                $iconColors = [
                    'blue'   => 'text-blue-600',
                    'green'  => 'text-green-600',
                    'purple' => 'text-purple-600',
                    'indigo' => 'text-indigo-600',
                    'gray'   => 'text-gray-400',
                ];
                $gradientColors = [
                    'blue'   => 'from-blue-200/80',
                    'green'  => 'from-green-200/80',
                    'purple' => 'from-purple-200/80',
                    'indigo' => 'from-indigo-200/80',
                    'gray'   => 'from-gray-200/80',
                ];
                $bgColors = [
                    'blue'   => 'bg-blue-50',
                    'green'  => 'bg-green-50',
                    'purple' => 'bg-purple-50',
                    'indigo' => 'bg-indigo-50',
                    'gray'   => 'bg-gray-50',
                ];
                $iconColor     = $iconColors[$module['color']]     ?? $iconColors['blue'];
                $gradientColor = $gradientColors[$module['color']] ?? $gradientColors['blue'];
                $bgColor       = $bgColors[$module['color']]       ?? $bgColors['blue'];
                $isDisabled    = !$module['enabled'];
                ?>
              
              <?php if ($isDisabled): ?>
                <div class="bg-bg-primary border border-border-light rounded-2xl p-6 opacity-50 cursor-not-allowed relative overflow-hidden">
                  <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/20 to-transparent opacity-60"></div>
                  <div class="relative z-10 w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center mb-4">
                    <i class="<?= $module['icon'] ?> text-xl <?= $iconColor ?>"></i>
                  </div>
                  <h3 class="relative z-10 text-sm font-semibold text-text-primary mb-2 leading-snug"><?= $module['title'] ?></h3>
                  <span class="relative z-10 inline-block px-2 py-1 bg-gray-200/50 text-gray-500 text-xs rounded-md leading-normal">
                    به زودی
                  </span>
                </div>
              <?php else: ?>
                <a 
                  href="<?= $module['url'] ?>" 
                  class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-<?= $module['color'] ?>-200 transition-all duration-200 relative overflow-hidden group">
                  <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/20 to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                  <div class="relative z-10 w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="<?= $module['icon'] ?> text-xl <?= $iconColor ?>"></i>
                  </div>
                  <h3 class="relative z-10 text-sm font-semibold text-text-primary group-hover:text-<?= $module['color'] ?>-700 transition-colors leading-snug">
                    <?= $module['title'] ?>
                  </h3>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        
        <!-- Recent Activity & Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Top Workflows -->
          <div class="lg:col-span-2">
            <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
              <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                <h3 class="text-lg font-semibold text-text-primary leading-snug">فرایندهای پرکار</h3>
                <a href="/dashboard/workflows/list.php" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium leading-normal">
                  مشاهده همه
                  <i class="fa-solid fa-arrow-left mr-1"></i>
                </a>
              </div>
              <div class="divide-y divide-border-light">
                <?php foreach ($topWorkflows as $workflow): ?>
                <div class="px-6 py-4 hover:bg-bg-secondary transition-colors duration-200">
                  <div class="flex items-start justify-between gap-4 mb-2">
                    <h4 class="text-base font-medium text-text-primary leading-snug flex-1"><?= $workflow['name'] ?></h4>
                    <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal flex-shrink-0">
                      <?= $workflow['tasks_count'] ?> کار
                    </span>
                  </div>
                  <div class="flex flex-wrap items-center gap-3 text-sm text-text-secondary leading-normal">
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-sitemap text-xs"></i>
                      <?= $workflow['department'] ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-layer-group text-xs"></i>
                      <?= $workflow['states_count'] ?> مرحله
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-text-muted">
                      <i class="fa-solid fa-code text-xs"></i>
                      <?= $workflow['slug'] ?>
                    </span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
          <!-- Task Status Statistics -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-text-primary mb-6 leading-snug">وضعیت کارها</h3>
            <div class="space-y-4">
              <?php
                $maxCount = max(array_column($taskStatusStats, 'count'));

foreach ($taskStatusStats as $status):
    $percentage = ($status['count'] / $maxCount) * 100;
    $barColors  = [
        'slate'  => 'from-slate-500 to-slate-600',
        'blue'   => 'from-blue-500 to-blue-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'green'  => 'from-green-500 to-green-600',
        'red'    => 'from-red-500 to-red-600',
    ];
    $barColor = $barColors[$status['color']] ?? $barColors['blue'];
    ?>
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-text-primary font-medium leading-normal"><?= $status['name'] ?></span>
                  <span class="text-sm text-text-secondary leading-normal"><?= $status['count'] ?></span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-l <?= $barColor ?> rounded-full transition-all duration-500" style="width: <?= $percentage ?>%"></div>
                </div>
              </div>
              <?php endforeach; ?>
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
