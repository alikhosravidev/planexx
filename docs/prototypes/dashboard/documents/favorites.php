<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'فایل‌های علاقه‌مندی';
$currentPage = 'favorites';
$currentFolder = '';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت اسناد', 'url' => '/dashboard/documents/index.php'],
    ['label' => 'علاقه‌مندی‌ها'],
];

// فایل‌های علاقه‌مندی (داده نمونه)
$favoriteFiles = [
    [
        'id' => 1,
        'name' => 'گزارش-فروش-آبان-۱۴۰۳.xlsx',
        'title' => 'گزارش فروش ماهانه آبان',
        'type' => 'excel',
        'size' => '۲.۴ MB',
        'folder' => 'فروش',
        'folderIcon' => 'fa-solid fa-chart-line',
        'folderColor' => 'green',
        'author' => 'احمد محمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=1',
        'date' => '۱۴۰۳/۰۹/۰۵',
        'tags' => ['گزارش', 'فروش', 'ماهانه'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۳/۰۹/۰۵'
    ],
    [
        'id' => 2,
        'name' => 'قرارداد-همکاری-شرکت-آلفا.pdf',
        'title' => 'قرارداد همکاری استراتژیک',
        'type' => 'pdf',
        'size' => '۵۶۸ KB',
        'folder' => 'اسناد استراتژیک',
        'folderIcon' => 'fa-solid fa-landmark',
        'folderColor' => 'purple',
        'author' => 'مریم احمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=5',
        'date' => '۱۴۰۳/۰۹/۰۴',
        'tags' => ['قرارداد', 'حقوقی'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۳/۰۹/۰۴'
    ],
    [
        'id' => 5,
        'name' => 'دستورالعمل-نصب-v2.pdf',
        'title' => 'دستورالعمل نصب و راه‌اندازی',
        'type' => 'pdf',
        'size' => '۳.۱ MB',
        'folder' => 'فنی و مهندسی',
        'folderIcon' => 'fa-solid fa-gears',
        'folderColor' => 'blue',
        'author' => 'علی رضایی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=7',
        'date' => '۱۴۰۳/۰۹/۰۱',
        'tags' => ['مستند', 'فنی', 'راهنما'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۳/۰۹/۰۲'
    ],
    [
        'id' => 8,
        'name' => 'صورتجلسه-هیئت-مدیره.docx',
        'title' => 'صورتجلسه هیئت مدیره',
        'type' => 'word',
        'size' => '۲۴۵ KB',
        'folder' => 'اسناد استراتژیک',
        'folderIcon' => 'fa-solid fa-landmark',
        'folderColor' => 'purple',
        'author' => 'محمد صادقی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=8',
        'date' => '۱۴۰۳/۰۸/۲۲',
        'tags' => ['جلسه', 'مدیریت'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۳/۰۸/۲۵'
    ],
    [
        'id' => 10,
        'name' => 'برنامه-استراتژیک-۱۴۰۳.pdf',
        'title' => 'برنامه استراتژیک سالانه',
        'type' => 'pdf',
        'size' => '۸.۵ MB',
        'folder' => 'اسناد استراتژیک',
        'folderIcon' => 'fa-solid fa-landmark',
        'folderColor' => 'purple',
        'author' => 'هیئت مدیره',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=11',
        'date' => '۱۴۰۲/۱۲/۱۵',
        'tags' => ['استراتژی', 'برنامه‌ریزی', 'سالانه'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۲/۱۲/۲۰'
    ],
    [
        'id' => 11,
        'name' => 'پرزنتیشن-معرفی-شرکت.pptx',
        'title' => 'معرفی شرکت و خدمات',
        'type' => 'powerpoint',
        'size' => '۱۵.۲ MB',
        'folder' => 'بازاریابی و تبلیغات',
        'folderIcon' => 'fa-solid fa-bullhorn',
        'folderColor' => 'pink',
        'author' => 'تیم مارکتینگ',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=9',
        'date' => '۱۴۰۳/۰۶/۱۰',
        'tags' => ['پرزنتیشن', 'معرفی', 'مارکتینگ'],
        'isTemporary' => false,
        'addedToFavorites' => '۱۴۰۳/۰۶/۱۵'
    ],
];

// آیکون و رنگ بر اساس نوع فایل
$fileTypeConfig = [
    'pdf' => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
    'word' => ['icon' => 'fa-solid fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
    'excel' => ['icon' => 'fa-solid fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
    'powerpoint' => ['icon' => 'fa-solid fa-file-powerpoint', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
    'image' => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
    'video' => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],
    'default' => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
];

$folderColorClasses = [
    'purple' => 'text-purple-500',
    'pink' => 'text-pink-500',
    'green' => 'text-green-500',
    'blue' => 'text-blue-500',
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('documents-sidebar', ['currentPage' => $currentPage, 'currentFolder' => $currentFolder]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0">
      
      <!-- Header -->
      <header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
        <div class="px-6 py-5">
          
          <!-- Top Row -->
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-star text-amber-500 text-xl"></i>
              </div>
              <div>
                <h1 class="text-xl font-bold text-text-primary leading-tight"><?= $pageTitle ?></h1>
                <nav class="flex items-center gap-2 text-xs text-text-muted mt-0.5">
                  <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index > 0): ?>
                      <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <?php endif; ?>
                    <?php if (isset($crumb['url']) && $index < count($breadcrumbs) - 1): ?>
                      <a href="<?= $crumb['url'] ?>" class="hover:text-primary transition-colors leading-normal"><?= $crumb['label'] ?></a>
                    <?php else: ?>
                      <span class="text-text-primary font-medium leading-normal"><?= $crumb['label'] ?></span>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </nav>
              </div>
            </div>
            
            <div class="flex items-center gap-2">
              <span class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium">
                <?= count($favoriteFiles) ?> فایل
              </span>
            </div>
          </div>
          
          <!-- Search -->
          <div class="relative max-w-xl">
            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
            <input 
              type="text" 
              placeholder="جستجو در علاقه‌مندی‌ها..." 
              class="w-full pr-11 pl-4 py-2.5 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200">
          </div>
          
        </div>
      </header>
      
      <!-- Content -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Grid View of Favorite Files -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($favoriteFiles as $file): ?>
            <?php 
            $typeConfig = $fileTypeConfig[$file['type']] ?? $fileTypeConfig['default'];
            $folderColorClass = $folderColorClasses[$file['folderColor']] ?? 'text-gray-500';
            ?>
            <div class="bg-bg-primary border border-border-light rounded-2xl p-5 hover:shadow-md hover:border-amber-200 transition-all duration-200 group">
              
              <!-- File Header -->
              <div class="flex items-start justify-between mb-4">
                <div class="relative">
                  <div class="w-14 h-14 <?= $typeConfig['bg'] ?> rounded-xl flex items-center justify-center">
                    <i class="<?= $typeConfig['icon'] ?> <?= $typeConfig['color'] ?> text-2xl"></i>
                  </div>
                  <!-- Uploader Avatar -->
                  <div class="absolute -bottom-1 -right-2 w-7 h-7 rounded-full border-2 border-white overflow-hidden" title="<?= $file['author'] ?>">
                    <img src="<?= $file['authorAvatar'] ?? 'https://i.pravatar.cc/100' ?>" alt="<?= $file['author'] ?>" class="w-full h-full object-cover">
                  </div>
                </div>
                <div class="flex items-center gap-1">
                  <!-- Remove from Favorites -->
                  <button 
                    class="w-8 h-8 flex items-center justify-center text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200"
                    title="حذف از علاقه‌مندی">
                    <i class="fa-solid fa-star"></i>
                  </button>
                  <!-- More Actions -->
                  <div class="relative" data-dropdown-container>
                    <button 
                      data-dropdown-toggle="fav-actions-<?= $file['id'] ?>"
                      class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                      <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div 
                      id="fav-actions-<?= $file['id'] ?>" 
                      data-dropdown
                      class="hidden absolute top-full left-0 mt-2 w-44 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                      <div class="p-2">
                        <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                          <i class="fa-solid fa-eye w-4"></i> مشاهده
                        </button>
                        <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                          <i class="fa-solid fa-download w-4"></i> دانلود
                        </button>
                        <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                          <i class="fa-solid fa-link w-4"></i> کپی لینک
                        </button>
                        <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                          <i class="fa-solid fa-folder-open w-4"></i> رفتن به پوشه
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- File Info -->
              <h3 class="text-sm font-semibold text-text-primary mb-1 leading-tight line-clamp-1"><?= $file['title'] ?></h3>
              <p class="text-xs text-text-muted mb-3 truncate"><?= $file['name'] ?></p>
              
              <!-- Tags -->
              <div class="flex flex-wrap gap-1 mb-4">
                <?php foreach (array_slice($file['tags'], 0, 3) as $tag): ?>
                  <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs"><?= $tag ?></span>
                <?php endforeach; ?>
              </div>
              
              <!-- Meta Info -->
              <div class="flex items-center justify-between text-xs text-text-muted pt-3 border-t border-border-light">
                <div class="flex items-center gap-1.5">
                  <i class="<?= $file['folderIcon'] ?> <?= $folderColorClass ?>"></i>
                  <span><?= $file['folder'] ?></span>
                </div>
                <span><?= $file['size'] ?></span>
              </div>
              
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Empty State -->
        <?php if (empty($favoriteFiles)): ?>
          <div class="text-center py-16">
            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-regular fa-star text-3xl text-amber-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-text-primary mb-2">هنوز فایلی به علاقه‌مندی‌ها اضافه نشده</h3>
            <p class="text-sm text-text-muted mb-6">با کلیک روی آیکون ستاره، فایل‌های مهم را به این لیست اضافه کنید</p>
            <a href="/dashboard/documents/index.php" class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm inline-flex items-center gap-2">
              <i class="fa-solid fa-folder-open"></i>
              <span>مرور فایل‌ها</span>
            </a>
          </div>
        <?php endif; ?>
        
      </div>
      
    </main>
    
  </div>
  
  <!-- JavaScript -->
  <script src="<?= asset('js/app.js') ?>"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      
      // Mobile Sidebar
      const mobileSidebarToggle = document.querySelector('[data-mobile-sidebar-toggle]');
      const mobileSidebarOverlay = document.querySelector('[data-mobile-sidebar-overlay]');
      const mobileSidebar = document.querySelector('[data-mobile-sidebar]');
      const mobileSidebarClose = document.querySelector('[data-mobile-sidebar-close]');
      
      function openMobileSidebar() {
        mobileSidebarOverlay?.classList.remove('hidden');
        mobileSidebar?.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
      }
      
      function closeMobileSidebar() {
        mobileSidebarOverlay?.classList.add('hidden');
        mobileSidebar?.classList.add('translate-x-full');
        document.body.style.overflow = '';
      }
      
      mobileSidebarToggle?.addEventListener('click', openMobileSidebar);
      mobileSidebarOverlay?.addEventListener('click', closeMobileSidebar);
      mobileSidebarClose?.addEventListener('click', closeMobileSidebar);
      
      // Folder Toggle
      document.querySelectorAll('[data-folder-toggle]').forEach(toggle => {
        toggle.addEventListener('click', function() {
          const folderId = this.getAttribute('data-folder-toggle');
          const children = document.querySelector(`[data-folder-children="${folderId}"]`);
          const arrow = this.querySelector('[data-folder-arrow]');
          if (children) {
            children.classList.toggle('hidden');
            arrow?.classList.toggle('-rotate-90');
          }
        });
      });
      
      // Dropdowns
      document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.stopPropagation();
          const targetId = this.getAttribute('data-dropdown-toggle');
          const dropdown = document.getElementById(targetId);
          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d.id !== targetId) d.classList.add('hidden');
          });
          dropdown?.classList.toggle('hidden');
        });
      });
      
      document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown]').forEach(d => d.classList.add('hidden'));
      });
      
    });
  </script>
  
</body>
</html>
