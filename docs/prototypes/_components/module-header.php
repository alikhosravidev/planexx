<?php
/**
 * کامپوننت Page Header برای ماژول‌های داخلی
 * شامل عنوان صفحه، توضیحات و دکمه‌های عملیاتی
 */

// دریافت متغیرها
$pageTitle       = $pageTitle       ?? 'عنوان صفحه';
$pageDescription = $pageDescription ?? null;
$breadcrumbs     = $breadcrumbs     ?? [];
$actionButtons   = $actionButtons   ?? [];

// اطلاعات کاربر (در پروژه واقعی از session می‌آید)
$userName   = $userName   ?? 'محمدرضا احمدی';
$userRole   = $userRole   ?? 'مدیر سیستم';
$userAvatar = $userAvatar ?? null;
?>

<header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
  <div class="px-6 py-5">
    <div class="flex items-center justify-between gap-6">
      
      <!-- Right Side: Title & Breadcrumb -->
      <div class="flex-1 min-w-0">
        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-text-primary leading-tight mb-2"><?= $pageTitle ?></h1>
        
        <!-- Breadcrumb -->
        <?php if (!empty($breadcrumbs)): ?>
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
        <?php endif; ?>
      </div>
      
      <!-- Center: Action Buttons (if any) -->
      <?php if (!empty($actionButtons)): ?>
      <div class="hidden lg:flex items-center gap-3">
        <?php foreach ($actionButtons as $button): ?>
          <?php
          $btnType      = $button['type'] ?? 'secondary';
            $btnClasses = [
                'primary'   => 'bg-primary text-white hover:bg-primary-dark',
                'secondary' => 'bg-bg-secondary text-text-primary hover:bg-slate-200',
                'outline'   => 'border-2 border-border-light text-text-primary hover:border-primary hover:text-primary',
            ];
            $classes = $btnClasses[$btnType] ?? $btnClasses['secondary'];
            ?>
          <a 
            href="<?= $button['url'] ?? '#' ?>" 
            class="<?= $classes ?> px-5 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal shadow-sm hover:shadow">
            <?php if (isset($button['icon'])): ?>
              <i class="<?= $button['icon'] ?>"></i>
            <?php endif; ?>
            <span><?= $button['label'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <!-- Left Side: User Menu -->
      <div class="relative flex-shrink-0" data-dropdown-container>
        <button 
          data-dropdown-toggle="user-menu"
          class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-bg-secondary transition-all duration-200">
          
          <!-- Avatar -->
          <div class="w-9 h-9 bg-gradient-to-br from-primary to-slate-700 rounded-full flex items-center justify-center">
            <?php if ($userAvatar): ?>
              <img src="<?= $userAvatar ?>" alt="<?= $userName ?>" class="w-full h-full rounded-full object-cover">
            <?php else: ?>
              <span class="text-white text-xs font-bold">
                <?= mb_substr($userName, 0, 1, 'UTF-8') ?>
              </span>
            <?php endif; ?>
          </div>
          
          <!-- User Info (Hidden on mobile) -->
          <div class="text-right hidden xl:block">
            <div class="text-sm font-medium text-text-primary leading-tight"><?= $userName ?></div>
            <div class="text-xs text-text-muted leading-tight"><?= $userRole ?></div>
          </div>
          
          <!-- Dropdown Icon -->
          <i class="fa-solid fa-chevron-down text-xs text-text-muted hidden xl:block"></i>
        </button>
        
        <!-- Dropdown Menu -->
        <div 
          id="user-menu"
          data-dropdown
          class="hidden absolute top-full left-0 mt-2 w-56 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
          
          <!-- User Info in Dropdown -->
          <div class="xl:hidden px-4 py-3 border-b border-border-light">
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
          </div>
          
          <div class="border-t border-border-light">
            <a href="/logout.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors leading-normal">
              <i class="fa-solid fa-sign-out w-5 text-center"></i>
              <span>خروج از حساب</span>
            </a>
          </div>
        </div>
      </div>
      
    </div>
    
    <!-- Mobile Action Buttons -->
    <?php if (!empty($actionButtons)): ?>
    <div class="lg:hidden mt-4 flex items-center gap-2 overflow-x-auto pb-1">
      <?php foreach ($actionButtons as $button): ?>
        <?php
        $btnType      = $button['type'] ?? 'secondary';
          $btnClasses = [
              'primary'   => 'bg-primary text-white',
              'secondary' => 'bg-bg-secondary text-text-primary',
              'outline'   => 'border border-border-light text-text-primary',
          ];
          $classes = $btnClasses[$btnType] ?? $btnClasses['secondary'];
          ?>
        <a 
          href="<?= $button['url'] ?? '#' ?>" 
          class="<?= $classes ?> px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2 leading-normal whitespace-nowrap">
          <?php if (isset($button['icon'])): ?>
            <i class="<?= $button['icon'] ?>"></i>
          <?php endif; ?>
          <span><?= $button['label'] ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</header>

