<?php
/**
 * کامپوننت Dashboard Header
 * شامل breadcrumb و منوی کاربری
 */

// دریافت متغیرها
$pageTitle   = $pageTitle     ?? 'داشبورد';
$breadcrumbs = $breadcrumbs ?? [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
];

// اطلاعات کاربر (در پروژه واقعی از session می‌آید)
$userName   = $userName     ?? 'محمدرضا احمدی';
$userRole   = $userRole     ?? 'مدیر سیستم';
$userAvatar = $userAvatar ?? null;
?>

<header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
  <div class="px-6 py-5">
    <div class="flex items-center justify-between">
      
      <!-- Right Side: Page Title & Breadcrumb -->
      <div class="flex-1">
        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-text-primary leading-tight mb-2"><?= $pageTitle ?></h1>
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-text-muted">
          <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <?php if ($index > 0): ?>
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            <?php endif; ?>
            
            <?php if (isset($crumb['url']) && $index < count($breadcrumbs) - 1): ?>
              <a href="<?= $crumb['url'] ?>" class="hover:text-primary transition-colors leading-normal">
                <?= $crumb['label'] ?>
              </a>
            <?php else: ?>
              <span class="text-text-primary font-medium leading-normal"><?= $crumb['label'] ?></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </nav>
      </div>
      
      <!-- Left Side: User Menu -->
      <div class="relative" data-dropdown-container>
        <button 
          data-dropdown-toggle="user-menu"
          class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-bg-secondary transition-all duration-200">
          
          <!-- Avatar -->
          <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-full flex items-center justify-center">
            <?php if ($userAvatar): ?>
              <img src="<?= $userAvatar ?>" alt="<?= $userName ?>" class="w-full h-full rounded-full object-cover">
            <?php else: ?>
              <span class="text-white text-sm font-bold">
                <?= mb_substr($userName, 0, 1, 'UTF-8') ?>
              </span>
            <?php endif; ?>
          </div>
          
          <!-- User Info -->
          <div class="text-right hidden sm:block">
            <div class="text-sm font-medium text-text-primary leading-tight"><?= $userName ?></div>
            <div class="text-xs text-text-muted leading-tight"><?= $userRole ?></div>
          </div>
          
          <!-- Dropdown Icon -->
          <i class="fa-solid fa-chevron-down text-xs text-text-muted"></i>
        </button>
        
        <!-- Dropdown Menu -->
        <div 
          id="user-menu"
          data-dropdown
          class="hidden absolute top-full left-0 mt-2 w-56 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
          
          <!-- User Info in Dropdown (Mobile) -->
          <div class="sm:hidden px-4 py-3 border-b border-border-light">
            <div class="text-sm font-medium text-text-primary leading-tight"><?= $userName ?></div>
            <div class="text-xs text-text-muted leading-tight mt-0.5"><?= $userRole ?></div>
          </div>
          
          <!-- Menu Items -->
          <div class="py-2">
            <a href="/dashboard/profile.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
              <i class="fa-solid fa-user w-5 text-center"></i>
              <span>پروفایل من</span>
            </a>
            
            <a href="/dashboard/settings.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
              <i class="fa-solid fa-cog w-5 text-center"></i>
              <span>تنظیمات</span>
            </a>
            
            <a href="/dashboard/notifications.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
              <i class="fa-solid fa-bell w-5 text-center"></i>
              <span>اعلان‌ها</span>
              <span class="mr-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">3</span>
            </a>
            
            <div class="border-t border-border-light my-2"></div>
            
            <a href="/dashboard/help.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-colors leading-normal">
              <i class="fa-solid fa-question-circle w-5 text-center"></i>
              <span>راهنما و پشتیبانی</span>
            </a>
            
            <div class="border-t border-border-light my-2"></div>
            
            <a href="/auth.php?action=logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors leading-normal">
              <i class="fa-solid fa-sign-out-alt w-5 text-center"></i>
              <span>خروج از حساب کاربری</span>
            </a>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</header>

