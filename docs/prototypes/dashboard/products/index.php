<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'داشبورد محصولات و لیست‌ها';
$currentPage = 'products-dashboard';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها'],
];

// آمار ماژول
$stats = [
    [
        'title'      => 'کل محصولات',
        'value'      => '۱,۲۴۸',
        'change'     => '+۱۸٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-box',
        'color'      => 'blue',
    ],
    [
        'title'      => 'محصولات فعال',
        'value'      => '۱,۱۰۲',
        'change'     => '+۱۲٪',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-check-circle',
        'color'      => 'green',
    ],
    [
        'title'      => 'تعداد لیست‌ها',
        'value'      => '۱۵',
        'change'     => '+۳',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-clipboard-list',
        'color'      => 'purple',
    ],
    [
        'title'      => 'آیتم‌های لیست‌ها',
        'value'      => '۴۳۶',
        'change'     => '+۲۴',
        'changeType' => 'increase',
        'icon'       => 'fa-solid fa-list-check',
        'color'      => 'orange',
    ],
];

// منوهای دسترسی سریع
$quickAccessModules = [
    [
        'title'   => 'مدیریت محصولات',
        'icon'    => 'fa-solid fa-box',
        'color'   => 'blue',
        'url'     => '/dashboard/products/products/list.php',
        'enabled' => true,
    ],
    [
        'title'   => 'افزودن محصول جدید',
        'icon'    => 'fa-solid fa-plus-circle',
        'color'   => 'green',
        'url'     => '/dashboard/products/products/create.php',
        'enabled' => true,
    ],
    [
        'title'   => 'مدیریت لیست‌ها',
        'icon'    => 'fa-solid fa-clipboard-list',
        'color'   => 'purple',
        'url'     => '/dashboard/products/lists/index.php',
        'enabled' => true,
    ],
    [
        'title'   => 'ایجاد لیست جدید',
        'icon'    => 'fa-solid fa-folder-plus',
        'color'   => 'orange',
        'url'     => '/dashboard/products/lists/index.php',
        'enabled' => true,
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('products-sidebar', ['currentPage' => $currentPage]); ?>
    
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
            
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md transition-all duration-200 relative overflow-hidden group">
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
                  'orange' => 'text-orange-600',
              ];
              $gradientColors = [
                  'blue'   => 'from-blue-200/80',
                  'green'  => 'from-green-200/80',
                  'purple' => 'from-purple-200/80',
                  'orange' => 'from-orange-200/80',
              ];
              $iconColor     = $iconColors[$module['color']]     ?? $iconColors['blue'];
              $gradientColor = $gradientColors[$module['color']] ?? $gradientColors['blue'];
              ?>
            
            <a href="<?= $module['url'] ?>" class="group bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md transition-all duration-200 flex flex-col items-center text-center gap-4 relative overflow-hidden">
              <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <div class="relative z-10 w-14 h-14 rounded-xl flex items-center justify-center bg-bg-secondary group-hover:scale-110 transition-transform duration-200">
                <i class="<?= $module['icon'] ?> text-2xl <?= $iconColor ?>"></i>
              </div>
              <span class="relative z-10 text-base font-semibold text-text-primary leading-normal"><?= $module['title'] ?></span>
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
