<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'داشبورد';
$currentPage = 'knowledge-base';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'پایگاه تجربه سازمانی'],
];

// آمار ماژول (در پروژه واقعی از دیتابیس)
$stats = [
    [
        'title'      => 'مجموع تجربیات',
        'value'      => '۱۸۴',
        'change'     => '+۲۳٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-lightbulb',
        'color'      => 'yellow',
    ],
    [
        'title'      => 'تجربیات این ماه',
        'value'      => '۳۸',
        'change'     => '+۱۵٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-calendar-days',
        'color'      => 'green',
    ],
    [
        'title'      => 'قالب‌های تجربه',
        'value'      => '۱۲',
        'change'     => '+۲',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-file-lines',
        'color'      => 'blue',
    ],
    [
        'title'      => 'دپارتمان‌های فعال',
        'value'      => '۸',
        'change'     => 'بدون تغییر',
        'changeType' => 'neutral',
        'icon'       => 'fa-solid fa-sitemap',
        'color'      => 'purple',
    ],
];

// منوهای دسترسی سریع
$quickAccessModules = [
    [
        'title'   => 'مدیریت تجربیات',
        'icon'    => 'fa-solid fa-lightbulb',
        'color'   => 'yellow',
        'url'     => '/dashboard/knowledge/experiences/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'قالب‌های تجربه',
        'icon'    => 'fa-solid fa-file-lines',
        'color'   => 'blue',
        'url'     => '/dashboard/knowledge/templates/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'ثبت تجربه جدید',
        'icon'    => 'fa-solid fa-plus-circle',
        'color'   => 'green',
        'url'     => '/dashboard/knowledge/experiences/create.php',
        'enabled' => true,
    ],
    [
        'title'   => 'گزارش‌گیری',
        'icon'    => 'fa-solid fa-chart-bar',
        'color'   => 'indigo',
        'url'     => '#',
        'enabled' => false,
    ],
];

// تجربیات اخیر
$recentExperiences = [
    [
        'title'      => 'بهینه‌سازی فرآیند قراردادها',
        'department' => 'مالی',
        'template'   => 'تجربه قراردادی',
        'author'     => 'احمد باقری',
        'date'       => '۱۴۰۳/۰۹/۰۵',
        'status'     => 'منتشر شده',
    ],
    [
        'title'      => 'کاهش زمان تولید محصول X',
        'department' => 'تولید',
        'template'   => 'بهبود فرآیند',
        'author'     => 'رضا صانعی',
        'date'       => '۱۴۰۳/۰۹/۰۴',
        'status'     => 'منتشر شده',
    ],
    [
        'title'      => 'راهکار جدید جذب نیرو',
        'department' => 'منابع انسانی',
        'template'   => 'استخدام',
        'author'     => 'مریم نوری',
        'date'       => '۱۴۰۳/۰۹/۰۳',
        'status'     => 'در حال بررسی',
    ],
    [
        'title'      => 'افزایش فروش محصولات دیجیتال',
        'department' => 'فروش',
        'template'   => 'استراتژی فروش',
        'author'     => 'فاطمه محمدی',
        'date'       => '۱۴۰۳/۰۹/۰۲',
        'status'     => 'منتشر شده',
    ],
    [
        'title'      => 'پیاده‌سازی CI/CD در پروژه',
        'department' => 'فنی',
        'template'   => 'توسعه نرم‌افزار',
        'author'     => 'سارا قاسمی',
        'date'       => '۱۴۰۳/۰۹/۰۱',
        'status'     => 'منتشر شده',
    ],
];

// آمار تجربیات به تفکیک دپارتمان
$departmentStats = [
    ['name' => 'فنی', 'count' => 45],
    ['name' => 'فروش', 'count' => 38],
    ['name' => 'مالی', 'count' => 32],
    ['name' => 'منابع انسانی', 'count' => 28],
    ['name' => 'تولید', 'count' => 25],
    ['name' => 'سایر', 'count' => 16],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('knowledge-sidebar', ['currentPage' => $currentPage]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Module Header -->
      <?php component('module-header', [
          'pageTitle'   => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Stats Cards - Minimal Design (مطابق با داشبورد اصلی) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
          <?php foreach ($stats as $stat): ?>
            <?php
            $iconColors = [
                'blue'   => 'text-blue-500',
                'green'  => 'text-green-500',
                'yellow' => 'text-yellow-500',
                'purple' => 'text-purple-500',
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
        
        <!-- Quick Access - Minimal Design (مطابق با داشبورد اصلی) -->
        <div class="mb-8">
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($quickAccessModules as $module): ?>
              <?php
                $iconColors = [
                    'blue'   => 'text-blue-600',
                    'green'  => 'text-green-600',
                    'purple' => 'text-purple-600',
                    'teal'   => 'text-teal-600',
                    'yellow' => 'text-yellow-600',
                    'gray'   => 'text-gray-400',
                ];
                $gradientColors = [
                    'blue'   => 'from-blue-200/80',
                    'green'  => 'from-green-200/80',
                    'purple' => 'from-purple-200/80',
                    'teal'   => 'from-teal-200/80',
                    'yellow' => 'from-yellow-200/80',
                    'gray'   => 'from-gray-200/80',
                ];
                $bgColors = [
                    'blue'   => 'bg-blue-50',
                    'green'  => 'bg-green-50',
                    'purple' => 'bg-purple-50',
                    'teal'   => 'bg-teal-50',
                    'yellow' => 'bg-yellow-50',
                    'gray'   => 'bg-gray-50',
                ];
                $iconColor     = $iconColors[$module['color']]         ?? $iconColors['blue'];
                $gradientColor = $gradientColors[$module['color']] ?? $gradientColors['blue'];
                $bgColor       = $bgColors[$module['color']]             ?? $bgColors['blue'];
                $isDisabled    = !$module['enabled'];
                ?>
              
              <?php if ($isDisabled): ?>
                <div class="bg-bg-primary border border-border-light rounded-2xl p-6 opacity-50 cursor-not-allowed relative overflow-hidden">
                  <!-- Gradient Background -->
                  <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/20 to-transparent opacity-60"></div>
                  
                  <!-- Icon -->
                  <div class="relative z-10 w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center mb-4">
                    <i class="<?= $module['icon'] ?> text-xl <?= $iconColor ?>"></i>
                  </div>
                  
                  <!-- Title -->
                  <h3 class="relative z-10 text-sm font-semibold text-text-primary mb-2 leading-snug"><?= $module['title'] ?></h3>
                  
                  <!-- Badge -->
                  <span class="relative z-10 inline-block px-2 py-1 bg-gray-200/50 text-gray-500 text-xs rounded-md leading-normal">
                    به زودی
                  </span>
                </div>
              <?php else: ?>
                <a 
                  href="<?= $module['url'] ?>" 
                  class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md hover:border-<?= $module['color'] ?>-200 transition-all duration-200 relative overflow-hidden group">
                  <!-- Gradient Background -->
                  <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/20 to-transparent opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                  
                  <!-- Icon -->
                  <div class="relative z-10 w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="<?= $module['icon'] ?> text-xl <?= $iconColor ?>"></i>
                  </div>
                  
                  <!-- Title -->
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
          
          <!-- Recent Experiences -->
          <div class="lg:col-span-2">
            <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
              <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                <h3 class="text-lg font-semibold text-text-primary leading-snug">تجربیات اخیر</h3>
                <a href="/dashboard/knowledge/experiences/list.php" class="text-sm text-teal-600 hover:text-teal-700 font-medium leading-normal">
                  مشاهده همه
                  <i class="fa-solid fa-arrow-left mr-1"></i>
                </a>
              </div>
              <div class="divide-y divide-border-light">
                <?php foreach ($recentExperiences as $exp): ?>
                <div class="px-6 py-4 hover:bg-bg-secondary transition-colors duration-200">
                  <div class="flex items-start justify-between gap-4 mb-2">
                    <h4 class="text-base font-medium text-text-primary leading-snug flex-1"><?= $exp['title'] ?></h4>
                    <?php if ($exp['status'] === 'منتشر شده'): ?>
                      <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal flex-shrink-0">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        منتشر شده
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal flex-shrink-0">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        در حال بررسی
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="flex flex-wrap items-center gap-3 text-sm text-text-secondary leading-normal">
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-sitemap text-xs"></i>
                      <?= $exp['department'] ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-file-lines text-xs"></i>
                      <?= $exp['template'] ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-user text-xs"></i>
                      <?= $exp['author'] ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                      <i class="fa-solid fa-calendar text-xs"></i>
                      <?= $exp['date'] ?>
                    </span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
          <!-- Department Statistics -->
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-text-primary mb-6 leading-snug">تجربیات به تفکیک دپارتمان</h3>
            <div class="space-y-4">
              <?php
                $maxCount = max(array_column($departmentStats, 'count'));

foreach ($departmentStats as $dept):
    $percentage = ($dept['count'] / $maxCount) * 100;
    ?>
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="text-sm text-text-primary font-medium leading-normal"><?= $dept['name'] ?></span>
                  <span class="text-sm text-text-secondary leading-normal"><?= $dept['count'] ?> تجربه</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-l from-teal-500 to-teal-600 rounded-full transition-all duration-500" style="width: <?= $percentage ?>%"></div>
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


