<?php
/**
 * کامپوننت Sidebar ماژول ساختار سازمانی
 */

$currentPage = $currentPage ?? '';

$orgMenuItems = [
    [
        'id'    => 'org-dashboard',
        'label' => 'داشبورد ماژول',
        'url'   => '/dashboard/org/index.php',
        'icon'  => 'fa-solid fa-chart-pie',
    ],
    [
        'id'    => 'org-employees',
        'label' => 'کارکنان',
        'url'   => '/dashboard/org/users/list.php?type=employee',
        'icon'  => 'fa-solid fa-user-tie',
    ],
    [
        'id'    => 'org-customers',
        'label' => 'مشتریان',
        'url'   => '/dashboard/org/users/list.php?type=customer',
        'icon'  => 'fa-solid fa-users',
    ],
    [
        'id'    => 'org-regular-users',
        'label' => 'کاربران عادی',
        'url'   => '/dashboard/org/users/list.php?type=user',
        'icon'  => 'fa-solid fa-user',
    ],
    [
        'id'    => 'org-departments',
        'label' => 'دپارتمان‌ها',
        'url'   => '/dashboard/org/departments/list.php',
        'icon'  => 'fa-solid fa-sitemap',
    ],
    [
        'id'    => 'org-roles',
        'label' => 'نقش‌ها و دسترسی‌ها',
        'url'   => '/dashboard/org/roles-permissions/roles.php',
        'icon'  => 'fa-solid fa-shield-halved',
    ],
];
?>

<!-- Sidebar - Desktop -->
<aside class="hidden lg:flex flex-col w-[280px] bg-bg-primary border-l border-border-light min-h-screen sticky top-0 self-start">
  
  <!-- Logo Section -->
  <div class="px-6 py-6 border-b border-border-light">
    <a href="/dashboard/index.php" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
      <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-arrow-right text-white text-lg"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs text-text-muted leading-tight">بازگشت به</p>
        <h1 class="text-base font-bold text-text-primary leading-tight truncate">داشبورد اصلی</h1>
      </div>
    </a>
  </div>
  
  <!-- Module Title -->
  <div class="px-6 py-4 bg-primary/5 border-b border-border-light">
    <div class="flex items-center gap-2.5">
      <i class="fa-solid fa-sitemap text-primary text-xl flex-shrink-0"></i>
      <h2 class="text-base font-bold text-text-primary leading-normal">ساختار سازمانی</h2>
    </div>
  </div>
  
  <!-- Navigation Menu -->
  <nav class="flex-1 overflow-y-auto px-3 py-4">
    <div class="space-y-1">
      <?php foreach ($orgMenuItems as $item): ?>
        <?php
        $itemUrlParts = parse_url($item['url']);
          $itemPath   = $itemUrlParts['path'] ?? '';
          $itemQuery  = [];

          if (isset($itemUrlParts['query'])) {
              parse_str($itemUrlParts['query'], $itemQuery);
          }

          $currentPath = $_SERVER['PHP_SELF'];
          $currentType = $_GET['type']   ?? '';
          $itemType    = $itemQuery['type'] ?? '';

          // Check if current page matches the menu item
          $pathMatches = str_contains($currentPath, str_replace('/dashboard/org/', '', $itemPath));
          $isActive    = $pathMatches && ($itemType === $currentType || (empty($itemType) && empty($currentType)));

          // Special case: if on users page without type, don't highlight any user submenu
          if (str_contains($currentPath, 'users/') && empty($currentType) && !empty($itemType)) {
              $isActive = false;
          }

          $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';

          if ($isActive) {
              $classes = $baseClasses . ' bg-primary text-white shadow-sm';
          } else {
              $classes = $baseClasses . ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
          }
          ?>
        
        <a href="<?= $item['url'] ?>" class="<?= $classes ?>">
          <i class="<?= $item['icon'] ?> w-5 text-center text-lg"></i>
          <span class="leading-normal"><?= $item['label'] ?></span>
          <?php if ($isActive): ?>
            <div class="mr-auto w-1.5 h-1.5 bg-white rounded-full"></div>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </nav>
  
  <!-- Footer Info -->
  <div class="px-6 py-4 border-t border-border-light">
    <div class="text-xs text-text-muted text-center leading-relaxed">
      ماژول ساختار سازمانی
    </div>
  </div>
  
</aside>

<!-- Mobile Sidebar Toggle Button -->
<button 
  data-mobile-sidebar-toggle
  class="lg:hidden fixed bottom-6 left-6 w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center z-40 hover:scale-110 transition-transform duration-200">
  <i class="fa-solid fa-bars text-xl"></i>
</button>

<!-- Mobile Sidebar Overlay -->
<div 
  data-mobile-sidebar-overlay
  class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden">
</div>

<!-- Mobile Sidebar -->
<aside 
  data-mobile-sidebar
  class="lg:hidden fixed top-0 right-0 w-[280px] h-screen bg-bg-primary shadow-2xl z-50 transform translate-x-full transition-transform duration-300">
  
  <!-- Mobile Header -->
  <div class="px-6 py-6 border-b border-border-light flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center">
        <i class="fa-solid fa-sitemap text-white text-lg"></i>
      </div>
      <div>
        <h1 class="text-lg font-bold text-text-primary leading-tight">ساختار سازمانی</h1>
        <p class="text-xs text-text-muted leading-tight">مدیریت سازمان</p>
      </div>
    </div>
    <button data-mobile-sidebar-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
      <i class="fa-solid fa-times"></i>
    </button>
  </div>
  
  <!-- Mobile Navigation -->
  <nav class="overflow-y-auto px-3 py-4" style="height: calc(100vh - 150px);">
    <div class="space-y-1">
      <!-- Back to Dashboard -->
      <a href="/dashboard/index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-all duration-200 border-b border-border-light mb-2 pb-3">
        <i class="fa-solid fa-arrow-right w-5 text-center text-lg"></i>
        <span class="leading-normal">بازگشت به داشبورد</span>
      </a>
      
      <?php foreach ($orgMenuItems as $item): ?>
        <?php
          $itemUrlParts = parse_url($item['url']);
          $itemPath     = $itemUrlParts['path'] ?? '';
          $itemQuery    = [];

          if (isset($itemUrlParts['query'])) {
              parse_str($itemUrlParts['query'], $itemQuery);
          }

          $currentPath = $_SERVER['PHP_SELF'];
          $currentType = $_GET['type']   ?? '';
          $itemType    = $itemQuery['type'] ?? '';

          $pathMatches = str_contains($currentPath, str_replace('/dashboard/org/', '', $itemPath));
          $isActive    = $pathMatches && ($itemType === $currentType || (empty($itemType) && empty($currentType)));

          if (str_contains($currentPath, 'users/') && empty($currentType) && !empty($itemType)) {
              $isActive = false;
          }

          $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';

          if ($isActive) {
              $classes = $baseClasses . ' bg-primary text-white shadow-sm';
          } else {
              $classes = $baseClasses . ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
          }
          ?>
        
        <a href="<?= $item['url'] ?>" class="<?= $classes ?>">
          <i class="<?= $item['icon'] ?> w-5 text-center text-lg"></i>
          <span class="leading-normal"><?= $item['label'] ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </nav>
  
  <!-- Mobile Footer -->
  <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-border-light bg-bg-primary">
    <div class="text-xs text-text-muted text-center leading-relaxed">
      ماژول ساختار سازمانی
    </div>
  </div>
  
</aside>

<script>
  // Mobile Sidebar Functionality
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('[data-mobile-sidebar-toggle]');
    const sidebar = document.querySelector('[data-mobile-sidebar]');
    const overlay = document.querySelector('[data-mobile-sidebar-overlay]');
    const closeBtn = document.querySelector('[data-mobile-sidebar-close]');
    
    function openSidebar() {
      sidebar.classList.remove('translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
      sidebar.classList.add('translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    }
    
    toggleBtn?.addEventListener('click', openSidebar);
    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
  });
</script>

