<?php
/**
 * کامپوننت Sidebar ماژول پایگاه تجربه سازمانی
 */

$currentPage = $currentPage ?? '';

$knowledgeMenuItems = [
    [
        'id'    => 'knowledge-dashboard',
        'label' => 'داشبورد ماژول',
        'url'   => '/dashboard/knowledge/index.php',
        'icon'  => 'fa-solid fa-chart-pie',
    ],
    [
        'id'    => 'knowledge-experiences',
        'label' => 'مدیریت تجربیات',
        'url'   => '/dashboard/knowledge/experiences/list.php',
        'icon'  => 'fa-solid fa-lightbulb',
    ],
    [
        'id'    => 'knowledge-templates',
        'label' => 'قالب‌های تجربه',
        'url'   => '/dashboard/knowledge/templates/list.php',
        'icon'  => 'fa-solid fa-file-lines',
    ],
];
?>

<!-- Sidebar - Desktop -->
<aside class="hidden lg:flex flex-col w-[280px] bg-bg-primary border-l border-border-light min-h-screen sticky top-0 self-start">
  
  <!-- Logo Section -->
  <div class="px-6 py-6 border-b border-border-light">
    <a href="/dashboard/index.php" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
      <div class="w-10 h-10 bg-gradient-to-br from-teal-600 to-teal-700 rounded-lg flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-arrow-right text-white text-lg"></i>
      </div>
      <div class="min-w-0">
        <p class="text-xs text-text-muted leading-tight">بازگشت به</p>
        <h1 class="text-base font-bold text-text-primary leading-tight truncate">داشبورد اصلی</h1>
      </div>
    </a>
  </div>
  
  <!-- Module Title -->
  <div class="px-6 py-4 bg-teal-50 border-b border-border-light">
    <div class="flex items-center gap-2.5">
      <i class="fa-solid fa-book text-teal-600 text-xl flex-shrink-0"></i>
      <h2 class="text-base font-bold text-text-primary leading-normal">پایگاه تجربه سازمانی</h2>
    </div>
  </div>
  
  <!-- Navigation Menu -->
  <nav class="flex-1 overflow-y-auto px-3 py-4">
    <div class="space-y-1">
      <?php foreach ($knowledgeMenuItems as $item): ?>
        <?php
        $isActive      = str_contains($_SERVER['PHP_SELF'], str_replace('/dashboard/knowledge/', '', $item['url']));
          $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';

          if ($isActive) {
              $classes = $baseClasses . ' bg-teal-600 text-white shadow-sm';
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
      ماژول پایگاه تجربه سازمانی
    </div>
  </div>
  
</aside>

<!-- Mobile Menu Button -->
<div class="lg:hidden fixed bottom-6 left-6 z-40">
  <button 
    data-mobile-menu-toggle
    class="w-14 h-14 bg-teal-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-teal-700 transition-all duration-200">
    <i class="fa-solid fa-bars text-xl"></i>
  </button>
</div>

<!-- Mobile Menu Overlay -->
<div 
  data-mobile-menu-overlay
  class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
  
  <!-- Slide-in Menu -->
  <div 
    data-mobile-menu
    class="absolute right-0 top-0 bottom-0 w-[280px] bg-bg-primary shadow-xl transform translate-x-full transition-transform duration-300">
    
    <!-- Close Button -->
    <div class="flex items-center justify-between px-6 py-6 border-b border-border-light">
      <h2 class="text-base font-bold text-text-primary">منو</h2>
      <button data-mobile-menu-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary transition-colors">
        <i class="fa-solid fa-times text-xl"></i>
      </button>
    </div>
    
    <!-- Module Title -->
    <div class="px-6 py-4 bg-teal-50 border-b border-border-light">
      <div class="flex items-center gap-2.5">
        <i class="fa-solid fa-book text-teal-600 text-xl flex-shrink-0"></i>
        <h2 class="text-sm font-bold text-text-primary leading-normal">پایگاه تجربه سازمانی</h2>
      </div>
    </div>
    
    <!-- Menu Items -->
    <nav class="px-3 py-4">
      <div class="space-y-1">
        <?php foreach ($knowledgeMenuItems as $item): ?>
          <?php
            $isActive    = str_contains($_SERVER['PHP_SELF'], str_replace('/dashboard/knowledge/', '', $item['url']));
            $baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-lg text-base font-medium transition-all duration-200';

            if ($isActive) {
                $classes = $baseClasses . ' bg-teal-600 text-white shadow-sm';
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
    
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.querySelector('[data-mobile-menu-toggle]');
  const menuOverlay = document.querySelector('[data-mobile-menu-overlay]');
  const menuClose = document.querySelector('[data-mobile-menu-close]');
  const menu = document.querySelector('[data-mobile-menu]');
  
  if (menuToggle && menuOverlay && menu) {
    menuToggle.addEventListener('click', () => {
      menuOverlay.classList.remove('hidden');
      setTimeout(() => menu.classList.remove('translate-x-full'), 10);
    });
    
    const closeMenu = () => {
      menu.classList.add('translate-x-full');
      setTimeout(() => menuOverlay.classList.add('hidden'), 300);
    };
    
    menuClose?.addEventListener('click', closeMenu);
    menuOverlay.addEventListener('click', (e) => {
      if (e.target === menuOverlay) closeMenu();
    });
  }
});
</script>

