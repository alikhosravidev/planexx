<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle     = 'فایل‌های اخیر';
$currentPage   = 'recent';
$currentFolder = '';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت اسناد', 'url' => '/dashboard/documents/index.php'],
    ['label' => 'اخیراً مشاهده شده'],
];

// فایل‌های اخیراً مشاهده شده (داده نمونه)
$recentFiles = [
    [
        'id'           => 1,
        'name'         => 'گزارش-فروش-آبان-۱۴۰۳.xlsx',
        'title'        => 'گزارش فروش ماهانه آبان',
        'type'         => 'excel',
        'size'         => '۲.۴ MB',
        'folder'       => 'فروش',
        'folderIcon'   => 'fa-solid fa-chart-line',
        'folderColor'  => 'green',
        'author'       => 'احمد محمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=1',
        'viewedAt'     => '۵ دقیقه پیش',
        'isFavorite'   => true,
    ],
    [
        'id'           => 2,
        'name'         => 'قرارداد-همکاری-شرکت-آلفا.pdf',
        'title'        => 'قرارداد همکاری استراتژیک',
        'type'         => 'pdf',
        'size'         => '۵۶۸ KB',
        'folder'       => 'اسناد استراتژیک',
        'folderIcon'   => 'fa-solid fa-landmark',
        'folderColor'  => 'purple',
        'author'       => 'مریم احمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=5',
        'viewedAt'     => '۱۵ دقیقه پیش',
        'isFavorite'   => true,
    ],
    [
        'id'           => 3,
        'name'         => 'پرزنتیشن-محصول-جدید.pptx',
        'title'        => 'معرفی محصول جدید',
        'type'         => 'powerpoint',
        'size'         => '۱۵.۲ MB',
        'folder'       => 'بازاریابی',
        'folderIcon'   => 'fa-solid fa-bullhorn',
        'folderColor'  => 'pink',
        'author'       => 'سارا قاسمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=9',
        'viewedAt'     => '۱ ساعت پیش',
        'isFavorite'   => false,
    ],
    [
        'id'           => 4,
        'name'         => 'لیست-قیمت-جدید.xlsx',
        'title'        => 'لیست قیمت محصولات',
        'type'         => 'excel',
        'size'         => '۸۴۵ KB',
        'folder'       => 'فروش',
        'folderIcon'   => 'fa-solid fa-chart-line',
        'folderColor'  => 'green',
        'author'       => 'رضا کریمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=3',
        'viewedAt'     => '۲ ساعت پیش',
        'isFavorite'   => false,
    ],
    [
        'id'           => 5,
        'name'         => 'دستورالعمل-نصب-v2.pdf',
        'title'        => 'دستورالعمل نصب و راه‌اندازی',
        'type'         => 'pdf',
        'size'         => '۳.۱ MB',
        'folder'       => 'فنی',
        'folderIcon'   => 'fa-solid fa-gears',
        'folderColor'  => 'blue',
        'author'       => 'علی رضایی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=7',
        'viewedAt'     => '۳ ساعت پیش',
        'isFavorite'   => true,
    ],
    [
        'id'           => 6,
        'name'         => 'تصویر-برند-اصلی.png',
        'title'        => 'لوگو و هویت بصری',
        'type'         => 'image',
        'size'         => '۲.۸ MB',
        'folder'       => 'بازاریابی',
        'folderIcon'   => 'fa-solid fa-bullhorn',
        'folderColor'  => 'pink',
        'author'       => 'فاطمه نوری',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=10',
        'viewedAt'     => 'دیروز',
        'isFavorite'   => false,
    ],
    [
        'id'           => 7,
        'name'         => 'صورتجلسه-هیئت-مدیره.docx',
        'title'        => 'صورتجلسه هیئت مدیره',
        'type'         => 'word',
        'size'         => '۲۴۵ KB',
        'folder'       => 'اسناد استراتژیک',
        'folderIcon'   => 'fa-solid fa-landmark',
        'folderColor'  => 'purple',
        'author'       => 'محمد صادقی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=8',
        'viewedAt'     => 'دیروز',
        'isFavorite'   => true,
    ],
    [
        'id'           => 8,
        'name'         => 'ویدیو-معرفی-شرکت.mp4',
        'title'        => 'ویدیو معرفی شرکت',
        'type'         => 'video',
        'size'         => '۱۲۵ MB',
        'folder'       => 'بازاریابی',
        'folderIcon'   => 'fa-solid fa-bullhorn',
        'folderColor'  => 'pink',
        'author'       => 'امیر حسینی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=12',
        'viewedAt'     => '۲ روز پیش',
        'isFavorite'   => false,
    ],
];

// آیکون و رنگ بر اساس نوع فایل
$fileTypeConfig = [
    'pdf'        => ['icon' => 'fa-solid fa-file-pdf', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
    'word'       => ['icon' => 'fa-solid fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
    'excel'      => ['icon' => 'fa-solid fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
    'powerpoint' => ['icon' => 'fa-solid fa-file-powerpoint', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
    'image'      => ['icon' => 'fa-solid fa-file-image', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
    'video'      => ['icon' => 'fa-solid fa-file-video', 'color' => 'text-pink-500', 'bg' => 'bg-pink-50'],
    'default'    => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
];

$folderColorClasses = [
    'purple' => 'text-purple-500',
    'pink'   => 'text-pink-500',
    'green'  => 'text-green-500',
    'blue'   => 'text-blue-500',
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
              <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xl"></i>
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
            
            <button class="text-sm text-text-muted hover:text-red-500 flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-red-50 transition-all duration-200">
              <i class="fa-solid fa-trash"></i>
              <span class="hidden sm:inline">پاک کردن تاریخچه</span>
            </button>
          </div>
          
          <!-- Search -->
          <div class="relative max-w-xl">
            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
            <input 
              type="text" 
              placeholder="جستجو در فایل‌های اخیر..." 
              class="w-full pr-11 pl-4 py-2.5 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200">
          </div>
          
        </div>
      </header>
      
      <!-- Content -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Recent Files List -->
        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
          
          <div class="divide-y divide-border-light">
            <?php foreach ($recentFiles as $file): ?>
              <?php
              $typeConfig         = $fileTypeConfig[$file['type']]                    ?? $fileTypeConfig['default'];
                $folderColorClass = $folderColorClasses[$file['folderColor']] ?? 'text-gray-500';
                ?>
              <div class="flex items-center gap-4 px-6 py-4 hover:bg-bg-secondary/50 transition-colors duration-200 group">
                
                <!-- File Icon -->
                <div class="relative flex-shrink-0">
                  <div class="w-12 h-12 <?= $typeConfig['bg'] ?> rounded-xl flex items-center justify-center">
                    <i class="<?= $typeConfig['icon'] ?> <?= $typeConfig['color'] ?> text-lg"></i>
                  </div>
                  <!-- Uploader Avatar -->
                  <div class="absolute -bottom-1 -right-2 w-6 h-6 rounded-full border-2 border-white overflow-hidden" title="<?= $file['author'] ?>">
                    <img src="<?= $file['authorAvatar'] ?? 'https://i.pravatar.cc/100' ?>" alt="<?= $file['author'] ?>" class="w-full h-full object-cover">
                  </div>
                </div>
                
                <!-- File Info -->
                <div class="flex-1 min-w-0">
                  <h3 class="text-sm font-medium text-text-primary leading-tight truncate"><?= $file['title'] ?></h3>
                  <p class="text-xs text-text-muted truncate mt-0.5"><?= $file['name'] ?></p>
                  <div class="flex items-center gap-4 mt-1.5">
                    <span class="inline-flex items-center gap-1.5 text-xs text-text-muted">
                      <i class="<?= $file['folderIcon'] ?> <?= $folderColorClass ?>"></i>
                      <?= $file['folder'] ?>
                    </span>
                    <span class="text-xs text-text-muted"><?= $file['size'] ?></span>
                  </div>
                </div>
                
                <!-- Viewed Time -->
                <div class="hidden sm:block text-left">
                  <span class="text-xs text-text-muted"><?= $file['viewedAt'] ?></span>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                  <button 
                    class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 <?= $file['isFavorite'] ? 'text-amber-500 hover:bg-amber-50' : 'text-text-muted hover:text-amber-500 hover:bg-amber-50' ?>"
                    title="<?= $file['isFavorite'] ? 'حذف از علاقه‌مندی' : 'افزودن به علاقه‌مندی' ?>">
                    <i class="<?= $file['isFavorite'] ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                  </button>
                  <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200" title="مشاهده">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-green-500 hover:bg-green-50 rounded-lg transition-all duration-200" title="دانلود">
                    <i class="fa-solid fa-download"></i>
                  </button>
                  <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200" title="رفتن به پوشه">
                    <i class="fa-solid fa-folder-open"></i>
                  </button>
                </div>
                
              </div>
            <?php endforeach; ?>
          </div>
          
        </div>
        
        <!-- Empty State -->
        <?php if (empty($recentFiles)): ?>
          <div class="text-center py-16">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-solid fa-clock-rotate-left text-3xl text-blue-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-text-primary mb-2">هنوز فایلی مشاهده نشده</h3>
            <p class="text-sm text-text-muted mb-6">فایل‌هایی که مشاهده می‌کنید اینجا نمایش داده می‌شوند</p>
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
      
    });
  </script>
  
</body>
</html>
