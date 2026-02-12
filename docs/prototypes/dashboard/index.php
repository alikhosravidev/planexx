<?php
/**
 * داشبورد اصلی
 * نمایش آمار کلی و دسترسی سریع به ماژول‌ها
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'داشبورد';
$activeModule = 'dashboard';

// Breadcrumb
$breadcrumbs = [
    ['label' => 'داشبورد']
];

// داده‌های آماری (در پروژه واقعی از دیتابیس می‌آید)
$stats = [
    [
        'title' => 'کاربران',
        'value' => '۲۴۸',
        'change' => '+۱۲٪',
        'changeType' => 'increase',
        'icon' => 'fa-solid fa-users',
        'color' => 'blue'
    ],
    [
        'title' => 'کارکنان',
        'value' => '۱۵۶',
        'change' => '+۸٪',
        'changeType' => 'increase',
        'icon' => 'fa-solid fa-user-tie',
        'color' => 'green'
    ],
    [
        'title' => 'تجربه‌ها',
        'value' => '۱۸۴',
        'change' => '+۲۳٪',
        'changeType' => 'increase',
        'icon' => 'fa-solid fa-lightbulb',
        'color' => 'yellow'
    ],
    [
        'title' => 'مشتریان',
        'value' => '۳۸۹',
        'change' => '-۳٪',
        'changeType' => 'decrease',
        'icon' => 'fa-solid fa-handshake',
        'color' => 'orange'
    ],
];

// ماژول‌های دسترسی سریع
$quickAccessModules = [
    [
        'title' => 'ساختار سازمانی',
        'icon' => 'fa-solid fa-sitemap',
        'color' => 'blue',
        'url' => '/dashboard/org/index.php',
        'enabled' => true
    ],
    [
        'title' => 'مدیریت اسناد و فایل‌ها',
        'icon' => 'fa-solid fa-folder-open',
        'color' => 'amber',
        'url' => '/dashboard/documents/index.php',
        'enabled' => true
    ],
    [
        'title' => 'مدیریت وظایف',
        'icon' => 'fa-solid fa-list-check',
        'color' => 'indigo',
        'url' => '/dashboard/workflows/index.php',
        'enabled' => true
    ],
    [
        'title' => 'پایگاه تجربه سازمانی',
        'icon' => 'fa-solid fa-book',
        'color' => 'teal',
        'url' => '/dashboard/knowledge/index.php',
        'enabled' => true
    ],
    [
        'title' => 'مالی و وصول مطالبات',
        'icon' => 'fa-solid fa-coins',
        'color' => 'green',
        'url' => '/dashboard/finance.php',
        'enabled' => true
    ],
    [
        'title' => 'CRM',
        'icon' => 'fa-solid fa-users-line',
        'color' => 'purple',
        'url' => '/dashboard/crm.php',
        'enabled' => true
    ],
    [
        'title' => 'محصولات و لیست‌ها',
        'icon' => 'fa-solid fa-boxes-stacked',
        'color' => 'stone',
        'url' => '/dashboard/products/index.php',
        'enabled' => true
    ],
];

// لود کامپوننت Head
component('head');
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('dashboard-sidebar', ['activeModule' => $activeModule]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Header -->
      <?php component('dashboard-header', [
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Stats Cards - Minimal Design -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
          <?php foreach ($stats as $stat): ?>
            <?php
            $iconColors = [
                'blue' => 'text-blue-500',
                'green' => 'text-green-500',
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
        
        <!-- Quick Access - Minimal Design -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
          <?php foreach ($quickAccessModules as $module): ?>
            <?php
            $iconColors = [
                'blue' => 'text-blue-600',
                'green' => 'text-green-600',
                'purple' => 'text-purple-600',
                'teal' => 'text-teal-600',
                'orange' => 'text-orange-600',
                'amber' => 'text-amber-600',
                'indigo' => 'text-indigo-600',
                'stone' => 'text-stone-600',
                'gray' => 'text-gray-400',
            ];
            $gradientColors = [
                'blue' => 'from-blue-300/60',
                'green' => 'from-green-300/60',
                'purple' => 'from-purple-300/60',
                'teal' => 'from-teal-300/60',
                'orange' => 'from-orange-300/60',
                'amber' => 'from-amber-300/60',
                'indigo' => 'from-indigo-300/60',
                'stone' => 'from-stone-400/50',
                'gray' => 'from-gray-300/60',
            ];
            $bgColors = [
                'blue' => 'bg-blue-50',
                'green' => 'bg-green-50',
                'purple' => 'bg-purple-50',
                'teal' => 'bg-teal-50',
                'orange' => 'bg-orange-50',
                'amber' => 'bg-amber-50',
                'indigo' => 'bg-indigo-50',
                'stone' => 'bg-stone-100',
                'gray' => 'bg-gray-50',
            ];
            $iconColor = $iconColors[$module['color']] ?? $iconColors['blue'];
            $gradientColor = $gradientColors[$module['color']] ?? $gradientColors['blue'];
            $bgColor = $bgColors[$module['color']] ?? $bgColors['blue'];
            $isDisabled = !$module['enabled'];
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
                <span class="relative z-10 inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                  <i class="fa-solid fa-clock text-[10px]"></i>
                  به زودی
                </span>
              </div>
            <?php else: ?>
              <a href="<?= $module['url'] ?>" 
                 class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:border-<?= $module['color'] ?>-300 hover:shadow-md hover:-translate-y-1 transition-all duration-200 group relative overflow-hidden">
                
                <!-- Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br <?= $gradientColor ?> via-white/40 to-transparent opacity-70 group-hover:opacity-90 transition-opacity duration-200"></div>
                
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
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        
      </div>
      
    </main>
    
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    // Mobile Sidebar Toggle
    const mobileToggle = document.querySelector('[data-mobile-sidebar-toggle]');
    const mobileSidebar = document.querySelector('[data-mobile-sidebar]');
    const mobileOverlay = document.querySelector('[data-mobile-sidebar-overlay]');
    const mobileClose = document.querySelector('[data-mobile-sidebar-close]');
    
    function openMobileSidebar() {
      mobileSidebar.classList.remove('translate-x-full');
      mobileOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
    
    function closeMobileSidebar() {
      mobileSidebar.classList.add('translate-x-full');
      mobileOverlay.classList.add('hidden');
      document.body.style.overflow = '';
    }
    
    if (mobileToggle) {
      mobileToggle.addEventListener('click', openMobileSidebar);
    }
    
    if (mobileClose) {
      mobileClose.addEventListener('click', closeMobileSidebar);
    }
    
    if (mobileOverlay) {
      mobileOverlay.addEventListener('click', closeMobileSidebar);
    }
  </script>
  
</body>
</html>

