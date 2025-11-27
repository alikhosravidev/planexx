<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle     = 'فایل‌های موقت';
$currentPage   = 'temporary';
$currentFolder = '';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت اسناد', 'url' => '/dashboard/documents/index.php'],
    ['label' => 'فایل‌های موقت'],
];

// فایل‌های موقت (داده نمونه)
$temporaryFiles = [
    [
        'id'           => 1,
        'name'         => 'لیست-قیمت-جدید.xlsx',
        'title'        => 'لیست قیمت محصولات - پیش‌نویس',
        'type'         => 'excel',
        'size'         => '۸۴۵ KB',
        'folder'       => 'فروش',
        'folderIcon'   => 'fa-solid fa-chart-line',
        'folderColor'  => 'green',
        'author'       => 'رضا کریمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=3',
        'uploadDate'   => '۱۴۰۳/۰۹/۰۲',
        'expiresIn'    => '۲۸ روز',
        'isFavorite'   => false,
    ],
    [
        'id'           => 2,
        'name'         => 'طرح-اولیه-کمپین.pptx',
        'title'        => 'طرح اولیه کمپین تبلیغاتی',
        'type'         => 'powerpoint',
        'size'         => '۸.۲ MB',
        'folder'       => 'بازاریابی',
        'folderIcon'   => 'fa-solid fa-bullhorn',
        'folderColor'  => 'pink',
        'author'       => 'سارا قاسمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=9',
        'uploadDate'   => '۱۴۰۳/۰۸/۲۵',
        'expiresIn'    => '۲۱ روز',
        'isFavorite'   => false,
    ],
    [
        'id'           => 3,
        'name'         => 'گزارش-تست-محصول.pdf',
        'title'        => 'گزارش تست‌های اولیه',
        'type'         => 'pdf',
        'size'         => '۲.۱ MB',
        'folder'       => 'فنی و مهندسی',
        'folderIcon'   => 'fa-solid fa-gears',
        'folderColor'  => 'blue',
        'author'       => 'علی رضایی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=7',
        'uploadDate'   => '۱۴۰۳/۰۸/۲۰',
        'expiresIn'    => '۱۶ روز',
        'isFavorite'   => false,
    ],
    [
        'id'           => 4,
        'name'         => 'یادداشت-جلسه-temp.docx',
        'title'        => 'یادداشت‌های جلسه داخلی',
        'type'         => 'word',
        'size'         => '۱۲۵ KB',
        'folder'       => 'اسناد استراتژیک',
        'folderIcon'   => 'fa-solid fa-landmark',
        'folderColor'  => 'purple',
        'author'       => 'محمد صادقی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=8',
        'uploadDate'   => '۱۴۰۳/۰۸/۱۵',
        'expiresIn'    => '۱۱ روز',
        'isFavorite'   => false,
    ],
    [
        'id'           => 5,
        'name'         => 'تصویر-پیش‌نویس-برند.png',
        'title'        => 'طرح اولیه لوگو',
        'type'         => 'image',
        'size'         => '۴.۵ MB',
        'folder'       => 'بازاریابی',
        'folderIcon'   => 'fa-solid fa-bullhorn',
        'folderColor'  => 'pink',
        'author'       => 'فاطمه نوری',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=10',
        'uploadDate'   => '۱۴۰۳/۰۸/۱۰',
        'expiresIn'    => '۶ روز',
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
              <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-amber-500 text-xl"></i>
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
              <span class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium hidden sm:inline-flex items-center gap-2">
                <i class="fa-solid fa-hourglass-half"></i>
                <?= count($temporaryFiles) ?> فایل موقت
              </span>
              <button class="text-sm text-red-500 hover:text-red-600 flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-red-50 transition-all duration-200">
                <i class="fa-solid fa-trash"></i>
                <span class="hidden sm:inline">پاکسازی همه</span>
              </button>
            </div>
          </div>
          
          <!-- Info Alert -->
          <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
            <div>
              <p class="text-sm text-amber-800 leading-relaxed">
                فایل‌های موقت پس از ۳۰ روز از تاریخ آپلود به طور خودکار حذف می‌شوند. 
                برای نگهداری دائمی، تیک "موقت" را از ویرایش فایل بردارید.
              </p>
            </div>
          </div>
          
        </div>
      </header>
      
      <!-- Content -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Temporary Files Table -->
        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
          
          <!-- Table Header -->
          <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <h2 class="text-lg font-semibold text-text-primary">لیست فایل‌های موقت</h2>
            <button class="text-sm text-primary hover:text-primary/80 flex items-center gap-2">
              <i class="fa-solid fa-check-double"></i>
              <span>انتخاب همه</span>
            </button>
          </div>
          
          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-bg-secondary border-b border-border-light">
                <tr>
                  <th class="text-right px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">
                    <label class="flex items-center gap-3 cursor-pointer">
                      <input type="checkbox" class="w-4 h-4 accent-primary rounded">
                      <span>فایل</span>
                    </label>
                  </th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">پوشه</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">حجم</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">تاریخ آپلود</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">انقضا</th>
                  <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-border-light">
                <?php foreach ($temporaryFiles as $file): ?>
                  <?php
                  $typeConfig         = $fileTypeConfig[$file['type']]                    ?? $fileTypeConfig['default'];
                    $folderColorClass = $folderColorClasses[$file['folderColor']] ?? 'text-gray-500';

                    // تعیین رنگ انقضا بر اساس زمان باقی‌مانده
                    $expiresNum   = intval($file['expiresIn']);
                    $expiresColor = $expiresNum <= 7 ? 'text-red-500 bg-red-50' : ($expiresNum <= 14 ? 'text-amber-500 bg-amber-50' : 'text-green-500 bg-green-50');
                    ?>
                  <tr class="hover:bg-bg-secondary/50 transition-colors duration-200 group">
                    <!-- File Info -->
                    <td class="px-6 py-4">
                      <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 accent-primary rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="relative flex-shrink-0">
                          <div class="w-11 h-11 <?= $typeConfig['bg'] ?> rounded-xl flex items-center justify-center">
                            <i class="<?= $typeConfig['icon'] ?> <?= $typeConfig['color'] ?> text-lg"></i>
                          </div>
                          <!-- Uploader Avatar -->
                          <div class="absolute -bottom-1 -right-2 w-6 h-6 rounded-full border-2 border-white overflow-hidden" title="<?= $file['author'] ?>">
                            <img src="<?= $file['authorAvatar'] ?? 'https://i.pravatar.cc/100' ?>" alt="<?= $file['author'] ?>" class="w-full h-full object-cover">
                          </div>
                        </div>
                        <div class="min-w-0">
                          <span class="text-sm font-medium text-text-primary truncate leading-tight block"><?= $file['title'] ?></span>
                          <p class="text-xs text-text-muted truncate leading-normal"><?= $file['name'] ?></p>
                        </div>
                      </label>
                    </td>
                    
                    <!-- Folder -->
                    <td class="px-4 py-4 hidden md:table-cell">
                      <span class="inline-flex items-center gap-1.5 text-sm text-text-secondary">
                        <i class="<?= $file['folderIcon'] ?> <?= $folderColorClass ?>"></i>
                        <?= $file['folder'] ?>
                      </span>
                    </td>
                    
                    <!-- Size -->
                    <td class="px-4 py-4 hidden sm:table-cell">
                      <span class="text-sm text-text-muted"><?= $file['size'] ?></span>
                    </td>
                    
                    <!-- Upload Date -->
                    <td class="px-4 py-4 hidden lg:table-cell">
                      <span class="text-sm text-text-muted"><?= $file['uploadDate'] ?></span>
                    </td>
                    
                    <!-- Expires -->
                    <td class="px-4 py-4">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium <?= $expiresColor ?>">
                        <i class="fa-solid fa-clock"></i>
                        <?= $file['expiresIn'] ?>
                      </span>
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-end gap-1">
                        <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200" title="مشاهده">
                          <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-green-500 hover:bg-green-50 rounded-lg transition-all duration-200" title="تبدیل به دائمی">
                          <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200" title="حذف">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          
        </div>
        
        <!-- Empty State -->
        <?php if (empty($temporaryFiles)): ?>
          <div class="text-center py-16">
            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-solid fa-hourglass text-3xl text-amber-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-text-primary mb-2">فایل موقتی وجود ندارد</h3>
            <p class="text-sm text-text-muted mb-6">فایل‌هایی که به صورت موقت آپلود می‌کنید اینجا نمایش داده می‌شوند</p>
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
