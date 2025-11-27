<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'داشبورد';
$currentPage = 'organizational-structure';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'ساختار سازمانی'],
];

// آمار ماژول (در پروژه واقعی از دیتابیس)
$stats = [
    [
        'title'      => 'تعداد کاربران',
        'value'      => '۲۴۷',
        'change'     => '+۱۲٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-users',
        'color'      => 'blue',
    ],
    [
        'title'      => 'کارمندان فعال',
        'value'      => '۱۸۹',
        'change'     => '+۸٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-user-tie',
        'color'      => 'green',
    ],
    [
        'title'      => 'دپارتمان‌ها',
        'value'      => '۲۴',
        'change'     => '+۲',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-sitemap',
        'color'      => 'purple',
    ],
    [
        'title'      => 'موقعیت‌های شغلی',
        'value'      => '۳۸',
        'change'     => '+۵',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-briefcase',
        'color'      => 'orange',
    ],
];

// منوهای دسترسی سریع
$quickAccessModules = [
    [
        'title'   => 'مدیریت کاربران',
        'icon'    => 'fa-solid fa-users',
        'color'   => 'blue',
        'url'     => '/dashboard/org/users/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'دپارتمان‌ها',
        'icon'    => 'fa-solid fa-sitemap',
        'color'   => 'purple',
        'url'     => '/dashboard/org/departments/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'موقعیت‌های شغلی',
        'icon'    => 'fa-solid fa-briefcase',
        'color'   => 'teal',
        'url'     => '/dashboard/org/job-positions/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'نقش‌ها و دسترسی‌ها',
        'icon'    => 'fa-solid fa-shield-halved',
        'color'   => 'green',
        'url'     => '/dashboard/org/roles-permissions/roles.php',
        'enabled' => true,
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('org-sidebar', ['currentPage' => $currentPage]); ?>
    
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
                'purple' => 'text-purple-500',
                'orange' => 'text-orange-500',
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
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <?php foreach ($quickAccessModules as $module): ?>
            <?php
              $iconColors = [
                  'blue'   => 'text-blue-600',
                  'green'  => 'text-green-600',
                  'purple' => 'text-purple-600',
                  'teal'   => 'text-teal-600',
                  'orange' => 'text-orange-600',
              ];
              $gradientColors = [
                  'blue'   => 'from-blue-200/80',
                  'green'  => 'from-green-200/80',
                  'purple' => 'from-purple-200/80',
                  'teal'   => 'from-teal-200/80',
                  'orange' => 'from-orange-200/80',
              ];
              $bgColors = [
                  'blue'   => 'bg-blue-50',
                  'green'  => 'bg-green-50',
                  'purple' => 'bg-purple-50',
                  'teal'   => 'bg-teal-50',
                  'orange' => 'bg-orange-50',
              ];
              $iconColor     = $iconColors[$module['color']]         ?? $iconColors['blue'];
              $gradientColor = $gradientColors[$module['color']] ?? $gradientColors['blue'];
              $bgColor       = $bgColors[$module['color']]             ?? $bgColors['blue'];
              ?>
            
            <a href="<?= $module['url'] ?>" 
               class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:border-<?= $module['color'] ?>-300 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group relative overflow-hidden">
              
              <!-- Gradient Background -->
              <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-200"></div>
              
              <!-- Icon -->
              <div class="relative z-10 w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                <i class="<?= $module['icon'] ?> text-xl <?= $iconColor ?>"></i>
              </div>
              
              <!-- Title -->
              <h3 class="relative z-10 text-sm font-semibold text-text-primary leading-snug flex items-center gap-2">
                <?= $module['title'] ?>
                <i class="fa-solid fa-arrow-left text-[10px] <?= $iconColor ?> opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
              </h3>
            </a>
          <?php endforeach; ?>
        </div>
        
      </div>
      
    </main>
    
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
</body>
</html>
        </div>
      </div>
      
      <!-- نمودار توزیع کاربران -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- توزیع بر اساس نوع کاربر -->
        <div class="bg-white border border-border-light rounded-2xl p-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">توزیع بر اساس نوع کاربر</h2>
          <div class="space-y-4">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-text-secondary leading-normal">کارمندان</span>
                <span class="text-sm font-semibold text-text-primary leading-normal">189 نفر (76%)</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: 76%"></div>
              </div>
            </div>
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-text-secondary leading-normal">مشتریان</span>
                <span class="text-sm font-semibold text-text-primary leading-normal">48 نفر (19%)</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 19%"></div>
              </div>
            </div>
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-text-secondary leading-normal">کاربران عادی</span>
                <span class="text-sm font-semibold text-text-primary leading-normal">10 نفر (5%)</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-gray-400 h-2.5 rounded-full" style="width: 5%"></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- آخرین فعالیت‌ها -->
        <div class="bg-white border border-border-light rounded-2xl p-6">
          <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">آخرین فعالیت‌ها</h2>
          <div class="space-y-4">
            <div class="flex items-start gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
              <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-plus text-green-600 text-sm"></i>
              </div>
              <div class="flex-1">
                <p class="text-sm text-text-primary leading-normal mb-1">کاربر جدید اضافه شد</p>
                <p class="text-xs text-text-muted leading-normal">علی احمدی - دپارتمان فروش</p>
              </div>
              <span class="text-xs text-text-muted leading-normal">2 ساعت پیش</span>
            </div>
            <div class="flex items-start gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
              <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-building text-blue-600 text-sm"></i>
              </div>
              <div class="flex-1">
                <p class="text-sm text-text-primary leading-normal mb-1">دپارتمان جدید ایجاد شد</p>
                <p class="text-xs text-text-muted leading-normal">دپارتمان بازاریابی دیجیتال</p>
              </div>
              <span class="text-xs text-text-muted leading-normal">5 ساعت پیش</span>
            </div>
            <div class="flex items-start gap-3 pb-4 border-b border-border-light last:border-0 last:pb-0">
              <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-shield-halved text-purple-600 text-sm"></i>
              </div>
              <div class="flex-1">
                <p class="text-sm text-text-primary leading-normal mb-1">نقش جدید تعریف شد</p>
                <p class="text-xs text-text-muted leading-normal">مدیر محصول</p>
              </div>
              <span class="text-xs text-text-muted leading-normal">1 روز پیش</span>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-briefcase text-orange-600 text-sm"></i>
              </div>
              <div class="flex-1">
                <p class="text-sm text-text-primary leading-normal mb-1">موقعیت شغلی جدید</p>
                <p class="text-xs text-text-muted leading-normal">کارشناس ارشد فروش</p>
              </div>
              <span class="text-xs text-text-muted leading-normal">2 روز پیش</span>
            </div>
          </div>
        </div>
      </div>
      
    </main>
  </div>
  
  <?php component('footer'); ?>
  
  <script src="<?= asset('js/app.js') ?>"></script>
  
</body>
</html>

