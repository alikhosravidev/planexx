<?php
/**
 * کامپوننت Sidebar داشبورد
 * نمایش منوی مینیمال و حرفه‌ای با دسترسی به ماژول‌های مختلف
 */

// آیتم‌های فعال منو
$activeModule = $activeModule ?? '';

// تعریف ماژول‌ها
$modules = [
    [
        'id' => 'dashboard',
        'label' => 'داشبورد',
        'url' => '/dashboard/index.php',
        'icon' => 'fa-solid fa-chart-line'
    ],
    [
        'id' => 'organization',
        'label' => 'ساختار سازمانی',
        'url' => '/dashboard/org/index.php',
        'icon' => 'fa-solid fa-sitemap'
    ],
    [
        'id' => 'knowledge',
        'label' => 'پایگاه تجربه سازمانی',
        'url' => '/dashboard/knowledge/index.php',
        'icon' => 'fa-solid fa-book'
    ],
    [
        'id' => 'documents',
        'label' => 'مدیریت اسناد و فایل‌ها',
        'url' => '/dashboard/documents/index.php',
        'icon' => 'fa-solid fa-folder-open'
    ],
    [
        'id' => 'products',
        'label' => 'محصولات و لیست‌ها',
        'url' => '/dashboard/products/index.php',
        'icon' => 'fa-solid fa-boxes-stacked'
    ],
    [
        'id' => 'finance',
        'label' => 'مالی و وصول مطالبات',
        'url' => '/dashboard/finance.php',
        'icon' => 'fa-solid fa-coins'
    ],
    [
        'id' => 'crm',
        'label' => 'CRM',
        'url' => '/dashboard/crm.php',
        'icon' => 'fa-solid fa-users-line'
    ],
    [
        'id' => 'tasks',
        'label' => 'مدیریت وظایف',
        'url' => '/dashboard/tasks.php',
        'icon' => 'fa-solid fa-tasks'
    ],
    [
        'id' => 'coming',
        'label' => 'ماژول بعدی',
        'url' => '#',
        'icon' => 'fa-solid fa-ellipsis',
        'disabled' => true
    ],
];
?>

<!-- Sidebar - Desktop -->
<aside class="hidden lg:flex flex-col w-[280px] bg-bg-primary border-l border-border-light h-screen sticky top-0">
  
  <!-- Logo Section -->
  <div class="px-6 py-6 border-b border-border-light">
    <div class="flex items-center gap-3 mb-2">
      <div class="w-12 h-12 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center">
        <i class="fa-solid fa-network-wired text-white text-xl"></i>
      </div>
      <div>
        <h1 class="text-xl font-bold text-text-primary leading-tight">ساپل</h1>
        <p class="text-sm text-text-muted leading-tight">سیستم یکپارچه درون سازمانی</p>
      </div>
    </div>
  </div>
  
  <!-- Navigation Menu -->
  <nav class="flex-1 overflow-y-auto px-3 py-4">
    <div class="space-y-1">
      <?php foreach ($modules as $module): ?>
        <?php 
        $isActive = $activeModule === $module['id'];
        $isDisabled = isset($module['disabled']) && $module['disabled'];
        $baseClasses = "flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200";
        
        if ($isDisabled) {
            $classes = $baseClasses . " text-text-muted bg-gray-50 cursor-not-allowed opacity-50";
        } elseif ($isActive) {
            $classes = $baseClasses . " bg-primary text-white shadow-sm";
        } else {
            $classes = $baseClasses . " text-text-secondary hover:bg-bg-secondary hover:text-text-primary";
        }
        ?>
        
        <?php if ($isDisabled): ?>
          <div class="<?= $classes ?>">
            <i class="<?= $module['icon'] ?> w-5 text-center text-lg"></i>
            <span class="leading-normal"><?= $module['label'] ?></span>
          </div>
        <?php else: ?>
          <a href="<?= $module['url'] ?>" class="<?= $classes ?>">
            <i class="<?= $module['icon'] ?> w-5 text-center text-lg"></i>
            <span class="leading-normal"><?= $module['label'] ?></span>
            <?php if ($isActive): ?>
              <div class="mr-auto w-1.5 h-1.5 bg-white rounded-full"></div>
            <?php endif; ?>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </nav>
  
  <!-- Footer Info -->
  <div class="px-6 py-4 border-t border-border-light">
    <div class="text-xs text-text-muted text-center leading-relaxed">
      نسخه 1.0.0
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
        <i class="fa-solid fa-network-wired text-white text-lg"></i>
      </div>
      <div>
        <h1 class="text-lg font-bold text-text-primary leading-tight">ساپل</h1>
        <p class="text-xs text-text-muted leading-tight">سیستم یکپارچه درون سازمانی</p>
      </div>
    </div>
    <button data-mobile-sidebar-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
      <i class="fa-solid fa-times"></i>
    </button>
  </div>
  
  <!-- Mobile Navigation -->
  <nav class="overflow-y-auto px-3 py-4" style="height: calc(100vh - 150px);">
    <div class="space-y-1">
      <?php foreach ($modules as $module): ?>
        <?php 
        $isActive = $activeModule === $module['id'];
        $isDisabled = isset($module['disabled']) && $module['disabled'];
        $baseClasses = "flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200";
        
        if ($isDisabled) {
            $classes = $baseClasses . " text-text-muted bg-gray-50 cursor-not-allowed opacity-50";
        } elseif ($isActive) {
            $classes = $baseClasses . " bg-primary text-white shadow-sm";
        } else {
            $classes = $baseClasses . " text-text-secondary hover:bg-bg-secondary hover:text-text-primary";
        }
        ?>
        
        <?php if ($isDisabled): ?>
          <div class="<?= $classes ?>">
            <i class="<?= $module['icon'] ?> w-5 text-center text-lg"></i>
            <span class="leading-normal"><?= $module['label'] ?></span>
          </div>
        <?php else: ?>
          <a href="<?= $module['url'] ?>" class="<?= $classes ?>">
            <i class="<?= $module['icon'] ?> w-5 text-center text-lg"></i>
            <span class="leading-normal"><?= $module['label'] ?></span>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </nav>
  
  <!-- Mobile Footer -->
  <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-border-light bg-bg-primary">
    <div class="text-xs text-text-muted text-center leading-relaxed">
      نسخه 1.0.0
    </div>
  </div>
  
</aside>

