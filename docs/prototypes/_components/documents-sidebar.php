<?php
/**
 * کامپوننت Sidebar ماژول مدیریت اسناد
 * نمایش منوی فولدرها و دسترسی سریع
 */

// آیتم‌های فعال منو
$currentPage   = $currentPage   ?? '';
$currentFolder = $currentFolder ?? '';

// فولدرهای پیش‌فرض سیستم
$systemFolders = [
    [
        'id'       => 'strategic',
        'label'    => 'اسناد استراتژیک',
        'icon'     => 'fa-solid fa-landmark',
        'color'    => 'purple',
        'children' => [
            ['id' => 'hr', 'label' => 'منابع انسانی', 'icon' => 'fa-solid fa-users'],
            ['id' => 'legal', 'label' => 'حقوقی', 'icon' => 'fa-solid fa-scale-balanced'],
            ['id' => 'management', 'label' => 'مدیریتی', 'icon' => 'fa-solid fa-briefcase'],
        ],
    ],
    [
        'id'       => 'marketing',
        'label'    => 'بازاریابی و سوشال',
        'icon'     => 'fa-solid fa-bullhorn',
        'color'    => 'pink',
        'children' => [
            ['id' => 'campaigns', 'label' => 'کمپین‌ها', 'icon' => 'fa-solid fa-rocket'],
            ['id' => 'branding', 'label' => 'برندینگ', 'icon' => 'fa-solid fa-palette'],
            ['id' => 'social', 'label' => 'شبکه‌های اجتماعی', 'icon' => 'fa-solid fa-share-nodes'],
        ],
    ],
    [
        'id'       => 'finance',
        'label'    => 'مالی و قراردادها',
        'icon'     => 'fa-solid fa-coins',
        'color'    => 'green',
        'children' => [
            ['id' => 'accounting', 'label' => 'حسابداری', 'icon' => 'fa-solid fa-calculator'],
            ['id' => 'contracts', 'label' => 'قراردادها', 'icon' => 'fa-solid fa-file-signature'],
            ['id' => 'invoices', 'label' => 'فاکتورها', 'icon' => 'fa-solid fa-file-invoice'],
        ],
    ],
    [
        'id'       => 'sales',
        'label'    => 'فروش',
        'icon'     => 'fa-solid fa-chart-line',
        'color'    => 'blue',
        'children' => [
            ['id' => 'proposals', 'label' => 'پیشنهادات', 'icon' => 'fa-solid fa-file-invoice'],
            ['id' => 'reports', 'label' => 'گزارشات فروش', 'icon' => 'fa-solid fa-chart-bar'],
        ],
    ],
    [
        'id'       => 'technical',
        'label'    => 'فنی و مهندسی',
        'icon'     => 'fa-solid fa-gears',
        'color'    => 'slate',
        'children' => [
            ['id' => 'specs', 'label' => 'مشخصات فنی', 'icon' => 'fa-solid fa-clipboard-list'],
            ['id' => 'manuals', 'label' => 'دفترچه راهنما', 'icon' => 'fa-solid fa-book'],
            ['id' => 'drawings', 'label' => 'نقشه‌ها', 'icon' => 'fa-solid fa-drafting-compass'],
        ],
    ],
    [
        'id'       => 'misc',
        'label'    => 'متفرقه',
        'icon'     => 'fa-solid fa-folder-tree',
        'color'    => 'amber',
        'children' => [],
    ],
    [
        'id'       => 'archive',
        'label'    => 'آرشیو',
        'icon'     => 'fa-solid fa-box-archive',
        'color'    => 'slate',
        'children' => [
            ['id' => 'archive-2024', 'label' => 'آرشیو ۱۴۰۳', 'icon' => 'fa-solid fa-folder'],
            ['id' => 'archive-2023', 'label' => 'آرشیو ۱۴۰۲', 'icon' => 'fa-solid fa-folder'],
            ['id' => 'archive-old', 'label' => 'آرشیو قدیمی', 'icon' => 'fa-solid fa-folder'],
        ],
    ],
];

// منوهای اصلی
$mainMenuItems = [
    [
        'id'    => 'all-files',
        'label' => 'همه فایل‌ها',
        'url'   => '/dashboard/documents/index.php',
        'icon'  => 'fa-solid fa-folder-open',
    ],
    [
        'id'    => 'favorites',
        'label' => 'علاقه‌مندی‌ها',
        'url'   => '/dashboard/documents/favorites.php',
        'icon'  => 'fa-solid fa-star',
    ],
    [
        'id'    => 'recent',
        'label' => 'اخیراً مشاهده شده',
        'url'   => '/dashboard/documents/recent.php',
        'icon'  => 'fa-solid fa-clock-rotate-left',
    ],
    [
        'id'    => 'temporary',
        'label' => 'فایل‌های موقت',
        'url'   => '/dashboard/documents/temporary.php',
        'icon'  => 'fa-solid fa-hourglass-half',
    ],
];
?>

<!-- Sidebar - Desktop -->
<aside class="hidden lg:flex flex-col w-[300px] bg-bg-primary border-l border-border-light h-screen sticky top-0 overflow-hidden">
  
  <!-- Logo Section -->
  <div class="px-5 py-5 border-b border-border-light">
    <a href="/dashboard/index.php" class="flex items-center gap-3 group">
      <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center">
        <i class="fa-solid fa-folder-open text-white text-lg"></i>
      </div>
      <div>
        <h1 class="text-lg font-bold text-text-primary leading-tight group-hover:text-primary transition-colors">مدیریت اسناد</h1>
        <p class="text-xs text-text-muted leading-tight">سیستم فایل‌ها و اسناد سازمانی</p>
      </div>
    </a>
  </div>
  
  <!-- Navigation Menu -->
  <nav class="flex-1 overflow-y-auto px-3 py-4">
    
    <!-- Main Menu -->
    <div class="mb-6">
      <div class="space-y-1">
        <?php foreach ($mainMenuItems as $item): ?>
          <?php
          $isActive  = $currentPage === $item['id'];
            $classes = 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200';
            $classes .= $isActive
                ? ' bg-primary text-white shadow-sm'
                : ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
            ?>
          <a href="<?= $item['url'] ?>" class="<?= $classes ?>">
            <i class="<?= $item['icon'] ?> w-5 text-center"></i>
            <span class="leading-normal"><?= $item['label'] ?></span>
            <?php if ($isActive): ?>
              <div class="mr-auto w-1.5 h-1.5 bg-white rounded-full"></div>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Folders Section -->
    <div class="mb-4">
      <div class="flex items-center justify-between px-4 mb-2">
        <span class="text-xs font-semibold text-text-muted uppercase tracking-wider">پوشه‌ها</span>
        <button class="w-6 h-6 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ایجاد پوشه جدید">
          <i class="fa-solid fa-plus text-xs"></i>
        </button>
      </div>
      
      <div class="space-y-1">
        <?php foreach ($systemFolders as $folder): ?>
          <?php
            $isExpanded   = $currentFolder === $folder['id'] || strpos($currentFolder, $folder['id']) === 0;
            $colorClasses = [
                'purple' => 'text-purple-500',
                'pink'   => 'text-pink-500',
                'green'  => 'text-green-500',
                'blue'   => 'text-blue-500',
                'slate'  => 'text-slate-500',
                'amber'  => 'text-amber-500',
            ];
            $iconColor = $colorClasses[$folder['color']] ?? 'text-gray-500';
            ?>
          <div class="folder-item" data-folder="<?= $folder['id'] ?>">
            <!-- Parent Folder -->
            <button 
              class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-all duration-200"
              data-folder-toggle="<?= $folder['id'] ?>">
              <i class="fa-solid fa-chevron-left text-[10px] text-text-muted transition-transform duration-200 <?= $isExpanded ? '-rotate-90' : '' ?>" data-folder-arrow></i>
              <i class="<?= $folder['icon'] ?> <?= $iconColor ?> w-5 text-center"></i>
              <span class="leading-normal flex-1 text-right"><?= $folder['label'] ?></span>
              <?php if (!empty($folder['children'])): ?>
                <span class="text-xs text-text-muted"><?= count($folder['children']) ?></span>
              <?php endif; ?>
            </button>
            
            <!-- Child Folders -->
            <?php if (!empty($folder['children'])): ?>
              <div class="folder-children pr-8 mt-1 space-y-0.5 <?= $isExpanded ? '' : 'hidden' ?>" data-folder-children="<?= $folder['id'] ?>">
                <?php foreach ($folder['children'] as $child): ?>
                  <?php
                    $isChildActive = $currentFolder === $child['id'];
                    $childClasses  = 'flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition-all duration-200';
                    $childClasses .= $isChildActive
                        ? ' bg-primary/10 text-primary font-medium'
                        : ' text-text-muted hover:bg-bg-secondary hover:text-text-secondary';
                    ?>
                  <a href="/dashboard/documents/folder.php?id=<?= $child['id'] ?>" class="<?= $childClasses ?>">
                    <i class="<?= $child['icon'] ?> w-4 text-center text-xs"></i>
                    <span class="leading-normal"><?= $child['label'] ?></span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    
  </nav>
  
  <!-- Mini Stats -->
  <div class="px-4 py-3 border-t border-border-light">
    <div class="grid grid-cols-2 gap-2">
      <div class="flex items-center gap-2 px-3 py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-file text-blue-500 text-xs"></i>
        <div>
          <span class="text-xs font-semibold text-text-primary persian-num">۱,۲۴۸</span>
          <span class="text-[10px] text-text-muted block leading-tight">فایل</span>
        </div>
      </div>
      <div class="flex items-center gap-2 px-3 py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-folder text-amber-500 text-xs"></i>
        <div>
          <span class="text-xs font-semibold text-text-primary persian-num">۸۶</span>
          <span class="text-[10px] text-text-muted block leading-tight">پوشه</span>
        </div>
      </div>
      <div class="flex items-center gap-2 px-3 py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-star text-yellow-500 text-xs"></i>
        <div>
          <span class="text-xs font-semibold text-text-primary persian-num">۴۵</span>
          <span class="text-[10px] text-text-muted block leading-tight">پسندیده</span>
        </div>
      </div>
      <div class="flex items-center gap-2 px-3 py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-hard-drive text-green-500 text-xs"></i>
        <div>
          <span class="text-xs font-semibold text-text-primary persian-num">۱۲.۵</span>
          <span class="text-[10px] text-text-muted block leading-tight">گیگابایت</span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Storage Info -->
  <div class="px-5 py-3 border-t border-border-light bg-bg-secondary/50">
    <div class="mb-1.5 flex items-center justify-between">
      <span class="text-[10px] text-text-muted">فضا</span>
      <span class="text-[10px] font-medium text-text-secondary">۱۲.۵ / ۵۰ GB</span>
    </div>
    <div class="h-1.5 bg-border-light rounded-full overflow-hidden">
      <div class="h-full bg-gradient-to-l from-primary to-blue-500 rounded-full" style="width: 25%"></div>
    </div>
  </div>
  
</aside>

<!-- Mobile Sidebar Toggle Button -->
<button 
  data-mobile-sidebar-toggle
  class="lg:hidden fixed bottom-6 left-6 w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center z-40 hover:scale-110 transition-transform duration-200">
  <i class="fa-solid fa-folder-open text-xl"></i>
</button>

<!-- Mobile Sidebar Overlay -->
<div 
  data-mobile-sidebar-overlay
  class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 hidden">
</div>

<!-- Mobile Sidebar -->
<aside 
  data-mobile-sidebar
  class="lg:hidden fixed top-0 right-0 w-[300px] h-screen bg-bg-primary shadow-2xl z-50 transform translate-x-full transition-transform duration-300 overflow-hidden flex flex-col">
  
  <!-- Mobile Header -->
  <div class="px-5 py-5 border-b border-border-light flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-gradient-to-br from-primary to-slate-700 rounded-lg flex items-center justify-center">
        <i class="fa-solid fa-folder-open text-white text-lg"></i>
      </div>
      <div>
        <h1 class="text-lg font-bold text-text-primary leading-tight">مدیریت اسناد</h1>
        <p class="text-xs text-text-muted leading-tight">سیستم فایل‌ها</p>
      </div>
    </div>
    <button data-mobile-sidebar-close class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200">
      <i class="fa-solid fa-times"></i>
    </button>
  </div>
  
  <!-- Mobile Navigation -->
  <nav class="flex-1 overflow-y-auto px-3 py-4">
    <!-- Main Menu -->
    <div class="mb-6">
      <div class="space-y-1">
        <?php foreach ($mainMenuItems as $item): ?>
          <?php
          $isActive  = $currentPage === $item['id'];
            $classes = 'flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200';
            $classes .= $isActive
                ? ' bg-primary text-white shadow-sm'
                : ' text-text-secondary hover:bg-bg-secondary hover:text-text-primary';
            ?>
          <a href="<?= $item['url'] ?>" class="<?= $classes ?>">
            <i class="<?= $item['icon'] ?> w-5 text-center"></i>
            <span class="leading-normal"><?= $item['label'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Folders Section -->
    <div class="mb-4">
      <div class="flex items-center justify-between px-4 mb-2">
        <span class="text-xs font-semibold text-text-muted uppercase tracking-wider">پوشه‌ها</span>
      </div>
      
      <div class="space-y-1">
        <?php foreach ($systemFolders as $folder): ?>
          <?php
            $colorClasses = [
                'purple' => 'text-purple-500',
                'pink'   => 'text-pink-500',
                'green'  => 'text-green-500',
                'blue'   => 'text-blue-500',
                'slate'  => 'text-slate-500',
                'amber'  => 'text-amber-500',
            ];
            $iconColor = $colorClasses[$folder['color']] ?? 'text-gray-500';
            ?>
          <a href="/dashboard/documents/folder.php?id=<?= $folder['id'] ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-text-secondary hover:bg-bg-secondary hover:text-text-primary transition-all duration-200">
            <i class="<?= $folder['icon'] ?> <?= $iconColor ?> w-5 text-center"></i>
            <span class="leading-normal"><?= $folder['label'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </nav>
  
  <!-- Mobile Mini Stats -->
  <div class="px-4 py-3 border-t border-border-light">
    <div class="grid grid-cols-4 gap-2">
      <div class="flex flex-col items-center py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-file text-blue-500 text-xs mb-1"></i>
        <span class="text-xs font-semibold text-text-primary persian-num">۱,۲۴۸</span>
      </div>
      <div class="flex flex-col items-center py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-folder text-amber-500 text-xs mb-1"></i>
        <span class="text-xs font-semibold text-text-primary persian-num">۸۶</span>
      </div>
      <div class="flex flex-col items-center py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-star text-yellow-500 text-xs mb-1"></i>
        <span class="text-xs font-semibold text-text-primary persian-num">۴۵</span>
      </div>
      <div class="flex flex-col items-center py-2 bg-bg-secondary/50 rounded-lg">
        <i class="fa-solid fa-hard-drive text-green-500 text-xs mb-1"></i>
        <span class="text-xs font-semibold text-text-primary persian-num">۱۲.۵</span>
      </div>
    </div>
  </div>
  
  <!-- Mobile Footer - Storage -->
  <div class="px-5 py-3 border-t border-border-light bg-bg-secondary/50">
    <div class="mb-1.5 flex items-center justify-between">
      <span class="text-[10px] text-text-muted">فضا</span>
      <span class="text-[10px] font-medium text-text-secondary">۱۲.۵ / ۵۰ GB</span>
    </div>
    <div class="h-1.5 bg-border-light rounded-full overflow-hidden">
      <div class="h-full bg-gradient-to-l from-primary to-blue-500 rounded-full" style="width: 25%"></div>
    </div>
  </div>
  
</aside>
