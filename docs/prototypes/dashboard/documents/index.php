<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'مدیریت اسناد و فایل‌ها';
$currentPage = 'all-files';
$currentFolder = '';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت اسناد'],
];

// آمار کلی
$stats = [
    ['label' => 'کل فایل‌ها', 'value' => '۱,۲۴۸', 'icon' => 'fa-solid fa-file', 'color' => 'blue'],
    ['label' => 'پوشه‌ها', 'value' => '۸۶', 'icon' => 'fa-solid fa-folder', 'color' => 'yellow'],
    ['label' => 'علاقه‌مندی‌ها', 'value' => '۴۵', 'icon' => 'fa-solid fa-star', 'color' => 'amber'],
    ['label' => 'فضای مصرفی', 'value' => '۱۲.۵ GB', 'icon' => 'fa-solid fa-hard-drive', 'color' => 'green'],
];

// پوشه‌های اصلی برای نمایش در صفحه
$mainFolders = [
    [
        'id' => 'strategic',
        'name' => 'اسناد استراتژیک',
        'icon' => 'fa-solid fa-landmark',
        'color' => 'purple',
        'filesCount' => 156,
        'foldersCount' => 4,
        'lastModified' => '۱۴۰۳/۰۹/۰۵'
    ],
    [
        'id' => 'marketing',
        'name' => 'بازاریابی و سوشال',
        'icon' => 'fa-solid fa-bullhorn',
        'color' => 'pink',
        'filesCount' => 234,
        'foldersCount' => 3,
        'lastModified' => '۱۴۰۳/۰۹/۰۴'
    ],
    [
        'id' => 'finance',
        'name' => 'مالی و قراردادها',
        'icon' => 'fa-solid fa-coins',
        'color' => 'green',
        'filesCount' => 198,
        'foldersCount' => 4,
        'lastModified' => '۱۴۰۳/۰۹/۰۵'
    ],
    [
        'id' => 'sales',
        'name' => 'فروش',
        'icon' => 'fa-solid fa-chart-line',
        'color' => 'blue',
        'filesCount' => 312,
        'foldersCount' => 3,
        'lastModified' => '۱۴۰۳/۰۹/۰۵'
    ],
    [
        'id' => 'technical',
        'name' => 'فنی و مهندسی',
        'icon' => 'fa-solid fa-gears',
        'color' => 'slate',
        'filesCount' => 189,
        'foldersCount' => 3,
        'lastModified' => '۱۴۰۳/۰۹/۰۳'
    ],
    [
        'id' => 'misc',
        'name' => 'متفرقه',
        'icon' => 'fa-solid fa-folder-tree',
        'color' => 'amber',
        'filesCount' => 78,
        'foldersCount' => 0,
        'lastModified' => '۱۴۰۳/۰۹/۰۱'
    ],
    [
        'id' => 'archive',
        'name' => 'آرشیو',
        'icon' => 'fa-solid fa-box-archive',
        'color' => 'slate',
        'filesCount' => 542,
        'foldersCount' => 3,
        'lastModified' => '۱۴۰۳/۰۸/۲۰'
    ],
];

// فایل‌های اخیر
$recentFiles = [
    [
        'id' => 1,
        'name' => 'گزارش-فروش-آبان-۱۴۰۳.xlsx',
        'title' => 'گزارش فروش ماهانه آبان',
        'type' => 'excel',
        'size' => '۲.۴ MB',
        'folder' => 'فروش',
        'author' => 'احمد محمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=1',
        'date' => '۱۴۰۳/۰۹/۰۵',
        'tags' => ['گزارش', 'فروش', 'ماهانه'],
        'isTemporary' => false,
        'isFavorite' => true
    ],
    [
        'id' => 2,
        'name' => 'قرارداد-همکاری-شرکت-آلفا.pdf',
        'title' => 'قرارداد همکاری استراتژیک',
        'type' => 'pdf',
        'size' => '۵۶۸ KB',
        'folder' => 'اسناد استراتژیک',
        'author' => 'مریم احمدی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=5',
        'date' => '۱۴۰۳/۰۹/۰۴',
        'tags' => ['قرارداد', 'حقوقی'],
        'isTemporary' => false,
        'isFavorite' => true
    ],
    [
        'id' => 3,
        'name' => 'پرزنتیشن-محصول-جدید.pptx',
        'title' => 'معرفی محصول جدید',
        'type' => 'powerpoint',
        'size' => '۱۵.۲ MB',
        'folder' => 'بازاریابی',
        'author' => 'سارا قاسمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=9',
        'date' => '۱۴۰۳/۰۹/۰۳',
        'tags' => ['پرزنتیشن', 'محصول', 'مارکتینگ'],
        'isTemporary' => false,
        'isFavorite' => false
    ],
    [
        'id' => 4,
        'name' => 'لیست-قیمت-جدید.xlsx',
        'title' => 'لیست قیمت محصولات',
        'type' => 'excel',
        'size' => '۸۴۵ KB',
        'folder' => 'فروش',
        'author' => 'رضا کریمی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=3',
        'date' => '۱۴۰۳/۰۹/۰۲',
        'tags' => ['قیمت', 'فروش'],
        'isTemporary' => true,
        'isFavorite' => false
    ],
    [
        'id' => 5,
        'name' => 'دستورالعمل-نصب-v2.pdf',
        'title' => 'دستورالعمل نصب و راه‌اندازی',
        'type' => 'pdf',
        'size' => '۳.۱ MB',
        'folder' => 'فنی',
        'author' => 'علی رضایی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=7',
        'date' => '۱۴۰۳/۰۹/۰۱',
        'tags' => ['مستند', 'فنی', 'راهنما'],
        'isTemporary' => false,
        'isFavorite' => true
    ],
    [
        'id' => 6,
        'name' => 'تصویر-برند-اصلی.png',
        'title' => 'لوگو و هویت بصری',
        'type' => 'image',
        'size' => '۲.۸ MB',
        'folder' => 'بازاریابی',
        'author' => 'فاطمه نوری',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=10',
        'date' => '۱۴۰۳/۰۸/۲۸',
        'tags' => ['برند', 'لوگو', 'گرافیک'],
        'isTemporary' => false,
        'isFavorite' => false
    ],
    [
        'id' => 7,
        'name' => 'ویدیو-معرفی-شرکت.mp4',
        'title' => 'ویدیو معرفی شرکت',
        'type' => 'video',
        'size' => '۱۲۵ MB',
        'folder' => 'بازاریابی',
        'author' => 'امیر حسینی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=12',
        'date' => '۱۴۰۳/۰۸/۲۵',
        'tags' => ['ویدیو', 'معرفی', 'تبلیغات'],
        'isTemporary' => false,
        'isFavorite' => false
    ],
    [
        'id' => 8,
        'name' => 'صورتجلسه-هیئت-مدیره.docx',
        'title' => 'صورتجلسه هیئت مدیره',
        'type' => 'word',
        'size' => '۲۴۵ KB',
        'folder' => 'اسناد استراتژیک',
        'author' => 'محمد صادقی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=8',
        'date' => '۱۴۰۳/۰۸/۲۲',
        'tags' => ['جلسه', 'مدیریت'],
        'isTemporary' => false,
        'isFavorite' => true
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
    'audio' => ['icon' => 'fa-solid fa-file-audio', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
    'archive' => ['icon' => 'fa-solid fa-file-zipper', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
    'default' => ['icon' => 'fa-solid fa-file', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'],
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
      
      <!-- Header with Search -->
      <header class="bg-bg-primary border-b border-border-light sticky top-0 z-30">
        <div class="px-6 py-5">
          
          <!-- Top Row: Title & Actions -->
          <div class="flex items-center justify-between mb-4">
            <div>
              <h1 class="text-2xl font-bold text-text-primary leading-tight mb-1"><?= $pageTitle ?></h1>
              <nav class="flex items-center gap-2 text-xs text-text-muted">
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
            
            <!-- Upload Button -->
            <button 
              data-upload-modal-open
              class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-sm">
              <i class="fa-solid fa-cloud-arrow-up"></i>
              <span>آپلود فایل</span>
            </button>
          </div>
          
          <!-- Search & Filters Row -->
          <div class="flex flex-col lg:flex-row gap-3">
            
            <!-- Search Input -->
            <div class="flex-1 relative">
              <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
              <input 
                type="text" 
                placeholder="جستجو در نام، تگ‌ها و عنوان فایل‌ها..." 
                class="w-full pr-11 pl-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200"
                data-search-input>
            </div>
            
            <!-- Filters -->
            <div class="flex items-center gap-2 flex-wrap">
              
              <!-- File Type Filter -->
              <div class="relative" data-dropdown-container>
                <button 
                  data-dropdown-toggle="filter-type"
                  class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary hover:border-primary hover:text-primary transition-all duration-200">
                  <i class="fa-solid fa-file-circle-question"></i>
                  <span>نوع فایل</span>
                  <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div 
                  id="filter-type" 
                  data-dropdown
                  class="hidden absolute top-full right-0 mt-2 w-48 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                  <div class="p-2">
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-bg-secondary rounded-lg cursor-pointer">
                      <input type="checkbox" class="accent-primary"> <span class="text-sm">PDF</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-bg-secondary rounded-lg cursor-pointer">
                      <input type="checkbox" class="accent-primary"> <span class="text-sm">Word</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-bg-secondary rounded-lg cursor-pointer">
                      <input type="checkbox" class="accent-primary"> <span class="text-sm">Excel</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-bg-secondary rounded-lg cursor-pointer">
                      <input type="checkbox" class="accent-primary"> <span class="text-sm">تصویر</span>
                    </label>
                    <label class="flex items-center gap-2 px-3 py-2 hover:bg-bg-secondary rounded-lg cursor-pointer">
                      <input type="checkbox" class="accent-primary"> <span class="text-sm">ویدیو</span>
                    </label>
                  </div>
                </div>
              </div>
              
              <!-- Date Filter -->
              <div class="relative" data-dropdown-container>
                <button 
                  data-dropdown-toggle="filter-date"
                  class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary hover:border-primary hover:text-primary transition-all duration-200">
                  <i class="fa-solid fa-calendar"></i>
                  <span>تاریخ</span>
                  <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div 
                  id="filter-date" 
                  data-dropdown
                  class="hidden absolute top-full right-0 mt-2 w-48 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                  <div class="p-2">
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg">امروز</button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg">هفته گذشته</button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg">ماه گذشته</button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg">سه ماه گذشته</button>
                  </div>
                </div>
              </div>
              
              <!-- Tags Filter -->
              <div class="relative" data-dropdown-container>
                <button 
                  data-dropdown-toggle="filter-tags"
                  class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary hover:border-primary hover:text-primary transition-all duration-200">
                  <i class="fa-solid fa-tags"></i>
                  <span>تگ‌ها</span>
                  <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div 
                  id="filter-tags" 
                  data-dropdown
                  class="hidden absolute top-full right-0 mt-2 w-56 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                  <div class="p-3">
                    <input type="text" placeholder="جستجوی تگ..." class="w-full px-3 py-2 border border-border-medium rounded-lg text-sm mb-2 outline-none focus:border-primary">
                    <div class="flex flex-wrap gap-1.5">
                      <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs cursor-pointer hover:bg-blue-100">گزارش</span>
                      <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-xs cursor-pointer hover:bg-green-100">فروش</span>
                      <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-xs cursor-pointer hover:bg-purple-100">قرارداد</span>
                      <span class="px-2 py-1 bg-orange-50 text-orange-600 rounded text-xs cursor-pointer hover:bg-orange-100">مستند</span>
                      <span class="px-2 py-1 bg-pink-50 text-pink-600 rounded text-xs cursor-pointer hover:bg-pink-100">مارکتینگ</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Show Temporary Toggle -->
              <label class="flex items-center gap-2 px-4 py-3 border border-border-medium rounded-xl text-sm text-text-secondary cursor-pointer hover:border-primary transition-all duration-200">
                <input type="checkbox" class="accent-primary">
                <i class="fa-solid fa-hourglass-half text-amber-500"></i>
                <span>موقت‌ها</span>
              </label>
              
            </div>
          </div>
          
        </div>
      </header>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Main Folders Grid -->
        <div class="mb-8">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-text-primary">پوشه‌های اصلی</h2>
            <button data-folder-modal-open class="text-sm text-primary hover:text-primary/80 font-medium flex items-center gap-1">
              <i class="fa-solid fa-plus"></i>
              <span>پوشه جدید</span>
            </button>
          </div>
          
          <div class="flex flex-wrap gap-5">
            <?php foreach ($mainFolders as $folder): ?>
              <?php
              $colorClasses = [
                  'purple' => ['main' => '#7C3AED', 'tab' => '#A78BFA'],
                  'pink' => ['main' => '#EC4899', 'tab' => '#F472B6'],
                  'green' => ['main' => '#10B981', 'tab' => '#34D399'],
                  'blue' => ['main' => '#3B82F6', 'tab' => '#60A5FA'],
                  'slate' => ['main' => '#64748B', 'tab' => '#94A3B8'],
                  'amber' => ['main' => '#F59E0B', 'tab' => '#FBBF24'],
              ];
              $colors = $colorClasses[$folder['color']] ?? $colorClasses['blue'];
              ?>
              <a href="/dashboard/documents/folder.php?id=<?= $folder['id'] ?>" 
                 class="group block w-[132px]">
                <div class="relative transition-all duration-200 group-hover:-translate-y-1 group-hover:drop-shadow-lg">
                  <!-- SVG Folder -->
                  <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                    <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $colors['tab'] ?>"></path>
                    <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $colors['main'] ?>"></path>
                  </svg>
                  <!-- Folder Content Overlay -->
                  <div class="absolute inset-0 flex flex-col items-center justify-center pt-[28%] px-2 pb-1">
                    <h3 class="text-[11px] sm:text-xs font-semibold text-white leading-tight text-center line-clamp-2 drop-shadow-sm"><?= $folder['name'] ?></h3>
                    <p class="text-[9px] sm:text-[10px] text-white/80 mt-0.5 persian-num"><?= $folder['filesCount'] ?> فایل</p>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        
        <!-- Recent Files Table -->
        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
          
          <!-- Table Header -->
          <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <h2 class="text-lg font-semibold text-text-primary">آخرین فایل‌ها</h2>
            <div class="flex items-center gap-2">
              <button class="w-9 h-9 flex items-center justify-center rounded-lg text-text-muted hover:bg-bg-secondary hover:text-primary transition-all duration-200" title="نمای لیستی">
                <i class="fa-solid fa-list"></i>
              </button>
              <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white" title="نمای کارتی">
                <i class="fa-solid fa-grip"></i>
              </button>
            </div>
          </div>
          
          <!-- Table Content -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-bg-secondary border-b border-border-light">
                <tr>
                  <th class="text-right px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">فایل</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">پوشه</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">تگ‌ها</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">حجم</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">تاریخ</th>
                  <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-border-light">
                <?php foreach ($recentFiles as $file): ?>
                  <?php 
                  $typeConfig = $fileTypeConfig[$file['type']] ?? $fileTypeConfig['default'];
                  ?>
                  <tr class="hover:bg-bg-secondary/50 transition-colors duration-200">
                    <!-- File Info -->
                    <td class="px-6 py-4">
                      <div class="flex items-center gap-3">
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
                          <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-text-primary truncate leading-tight"><?= $file['title'] ?></span>
                            <?php if ($file['isTemporary']): ?>
                              <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-medium">موقت</span>
                            <?php endif; ?>
                          </div>
                          <p class="text-xs text-text-muted truncate leading-normal"><?= $file['name'] ?></p>
                        </div>
                      </div>
                    </td>
                    
                    <!-- Folder -->
                    <td class="px-4 py-4 hidden lg:table-cell">
                      <span class="text-sm text-text-secondary"><?= $file['folder'] ?></span>
                    </td>
                    
                    <!-- Tags -->
                    <td class="px-4 py-4 hidden md:table-cell">
                      <div class="flex flex-wrap gap-1">
                        <?php foreach (array_slice($file['tags'], 0, 2) as $tag): ?>
                          <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs"><?= $tag ?></span>
                        <?php endforeach; ?>
                        <?php if (count($file['tags']) > 2): ?>
                          <span class="px-2 py-0.5 bg-bg-secondary text-text-muted rounded text-xs">+<?= count($file['tags']) - 2 ?></span>
                        <?php endif; ?>
                      </div>
                    </td>
                    
                    <!-- Size -->
                    <td class="px-4 py-4 hidden sm:table-cell">
                      <span class="text-sm text-text-muted"><?= $file['size'] ?></span>
                    </td>
                    
                    <!-- Date -->
                    <td class="px-4 py-4 hidden lg:table-cell">
                      <span class="text-sm text-text-muted"><?= $file['date'] ?></span>
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-end gap-1">
                        <!-- Favorite -->
                        <button 
                          class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 <?= $file['isFavorite'] ? 'text-amber-500 hover:bg-amber-50' : 'text-text-muted hover:text-amber-500 hover:bg-amber-50' ?>"
                          title="<?= $file['isFavorite'] ? 'حذف از علاقه‌مندی' : 'افزودن به علاقه‌مندی' ?>">
                          <i class="<?= $file['isFavorite'] ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                        </button>
                        
                        <!-- Preview -->
                        <button 
                          data-preview-modal-open
                          data-file-id="<?= $file['id'] ?>"
                          data-file-type="<?= $file['type'] ?>"
                          data-file-name="<?= $file['name'] ?>"
                          data-file-title="<?= $file['title'] ?>"
                          class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                          title="مشاهده">
                          <i class="fa-solid fa-eye"></i>
                        </button>
                        
                        <!-- Copy Link -->
                        <button 
                          class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200"
                          title="کپی لینک">
                          <i class="fa-solid fa-link"></i>
                        </button>
                        
                        <!-- Edit -->
                        <button 
                          class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-green-500 hover:bg-green-50 rounded-lg transition-all duration-200"
                          title="ویرایش">
                          <i class="fa-solid fa-pen"></i>
                        </button>
                        
                        <!-- Delete -->
                        <button 
                          class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                          title="حذف">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="px-6 py-4 border-t border-border-light flex items-center justify-between">
            <div class="text-sm text-text-muted">
              نمایش ۱ تا ۸ از ۱,۲۴۸ فایل
            </div>
            <div class="flex items-center gap-1">
              <button class="w-9 h-9 flex items-center justify-center text-text-muted hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
              <button class="w-9 h-9 flex items-center justify-center bg-primary text-white rounded-lg">۱</button>
              <button class="w-9 h-9 flex items-center justify-center text-text-secondary hover:bg-bg-secondary rounded-lg transition-all duration-200">۲</button>
              <button class="w-9 h-9 flex items-center justify-center text-text-secondary hover:bg-bg-secondary rounded-lg transition-all duration-200">۳</button>
              <span class="px-2 text-text-muted">...</span>
              <button class="w-9 h-9 flex items-center justify-center text-text-secondary hover:bg-bg-secondary rounded-lg transition-all duration-200">۱۵۶</button>
              <button class="w-9 h-9 flex items-center justify-center text-text-muted hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-chevron-left"></i>
              </button>
            </div>
          </div>
          
        </div>
        
      </div>
      
    </main>
    
  </div>
  
  <!-- New Folder Modal -->
  <div 
    data-folder-modal
    class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-folder-modal-close></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[500px] w-full max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
          <div>
            <h3 class="text-xl font-semibold text-text-primary leading-snug">ایجاد پوشه جدید</h3>
            <p class="text-sm text-text-muted mt-1">در <span class="text-primary font-medium" data-folder-parent-name>ریشه اسناد</span></p>
          </div>
          <button data-folder-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
            <i class="fa-solid fa-times"></i>
          </button>
        </div>
        
        <!-- Body -->
        <div class="p-6">
          
          <!-- Folder Name -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-2">نام پوشه</label>
            <input 
              type="text" 
              data-folder-name-input
              class="w-full px-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary outline-none focus:border-primary focus:shadow-focus transition-all duration-200"
              placeholder="نام پوشه را وارد کنید...">
          </div>
          
          <!-- Parent Folder -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-2">پوشه والد</label>
            <select 
              data-folder-parent-select
              class="w-full px-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary outline-none focus:border-primary focus:shadow-focus transition-all duration-200 cursor-pointer bg-white">
              <option value="">ریشه اسناد (بدون والد)</option>
              <option value="strategic">اسناد استراتژیک</option>
              <option value="marketing">بازاریابی و سوشال</option>
              <option value="finance">مالی و قراردادها</option>
              <option value="sales">فروش</option>
              <option value="technical">فنی و مهندسی</option>
              <option value="misc">متفرقه</option>
              <option value="archive">آرشیو</option>
            </select>
          </div>
          
          <!-- Folder Color -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-text-secondary mb-3">رنگ پوشه</label>
            <div class="flex flex-wrap gap-3">
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="purple" class="hidden peer" checked>
                <div class="w-10 h-10 rounded-xl bg-purple-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-purple-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="pink" class="hidden peer">
                <div class="w-10 h-10 rounded-xl bg-pink-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="green" class="hidden peer">
                <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-green-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="blue" class="hidden peer">
                <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-blue-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="amber" class="hidden peer">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="slate" class="hidden peer">
                <div class="w-10 h-10 rounded-xl bg-slate-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-slate-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
            </div>
          </div>
          
          <!-- Preview -->
          <div class="p-4 bg-bg-secondary rounded-xl">
            <p class="text-xs text-text-muted mb-3">پیش‌نمایش پوشه:</p>
            <div class="flex items-center gap-3">
              <div class="w-14" data-folder-preview>
                <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                  <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="#A78BFA"></path>
                  <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="#7C3AED"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-text-primary" data-folder-preview-name>نام پوشه</p>
                <p class="text-xs text-text-muted">۰ فایل</p>
              </div>
            </div>
          </div>
          
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3">
          <button 
            data-folder-modal-close
            class="px-5 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-sm">
            انصراف
          </button>
          <button 
            data-folder-create-btn
            class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm flex items-center gap-2">
            <i class="fa-solid fa-folder-plus"></i>
            <span>ایجاد پوشه</span>
          </button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- Upload Modal -->
  <div 
    data-upload-modal
    class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-upload-modal-close></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[600px] w-full max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
          <h3 class="text-xl font-semibold text-text-primary leading-snug">آپلود فایل جدید</h3>
          <button data-upload-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
            <i class="fa-solid fa-times"></i>
          </button>
        </div>
        
        <!-- Body -->
        <div class="p-6">
          
          <!-- Drop Zone -->
          <label 
            data-drop-zone
            class="border-2 border-dashed border-border-medium rounded-2xl p-10 text-center mb-6 hover:border-primary hover:bg-primary/5 transition-all duration-200 cursor-pointer block">
            <div class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fa-solid fa-cloud-arrow-up text-2xl text-primary"></i>
            </div>
            <h4 class="text-lg font-semibold text-text-primary mb-2">فایل‌ها را اینجا رها کنید</h4>
            <p class="text-sm text-text-muted mb-4">یا کلیک کنید تا فایل انتخاب شود</p>
            <input type="file" class="hidden" multiple data-file-input>
            <span class="bg-bg-secondary text-text-secondary px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-border-light transition-all duration-200 inline-block">
              انتخاب فایل
            </span>
          </label>
          
          <!-- File Title -->
          <div class="mb-4">
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[120px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  عنوان فایل
                </label>
                <input 
                  type="text" 
                  class="flex-1 px-4 py-3.5 text-sm text-text-primary outline-none bg-transparent leading-normal"
                  placeholder="عنوان توصیفی فایل را وارد کنید">
              </div>
            </div>
          </div>
          
          <!-- Folder Selection -->
          <div class="mb-4">
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[120px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  پوشه مقصد
                </label>
                <select class="flex-1 px-4 py-3.5 text-sm text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                  <option>انتخاب پوشه...</option>
                  <option>اسناد استراتژیک</option>
                  <option>بازاریابی و تبلیغات</option>
                  <option>فروش</option>
                  <option>فنی و مهندسی</option>
                  <option>آرشیو</option>
                  <option>متفرقه</option>
                </select>
              </div>
            </div>
          </div>
          
          <!-- Tags -->
          <div class="mb-4">
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[120px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تگ‌ها
                </label>
                <div class="flex-1 px-4 py-2.5 flex flex-wrap items-center gap-2">
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs">
                    گزارش
                    <button class="hover:text-blue-800"><i class="fa-solid fa-times text-[10px]"></i></button>
                  </span>
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs">
                    فروش
                    <button class="hover:text-green-800"><i class="fa-solid fa-times text-[10px]"></i></button>
                  </span>
                  <input 
                    type="text" 
                    class="flex-1 min-w-[100px] text-sm text-text-primary outline-none bg-transparent leading-normal"
                    placeholder="تگ جدید + Enter">
                </div>
              </div>
            </div>
          </div>
          
          <!-- Temporary File Toggle -->
          <div class="mb-6">
            <label class="flex items-center gap-3 p-4 border border-border-medium rounded-xl cursor-pointer hover:border-primary transition-all duration-200">
              <input type="checkbox" class="w-5 h-5 accent-primary">
              <div>
                <span class="text-sm font-medium text-text-primary">فایل موقت</span>
                <p class="text-xs text-text-muted mt-0.5">این فایل به صورت موقت ذخیره می‌شود و قابل پاکسازی است</p>
              </div>
              <i class="fa-solid fa-hourglass-half text-amber-500 mr-auto"></i>
            </label>
          </div>
          
          <!-- Upload Progress (Hidden by default) -->
          <div class="hidden mb-6" data-upload-progress>
            <div class="flex items-center gap-3 p-4 bg-bg-secondary rounded-xl">
              <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-file-excel text-green-600"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-sm font-medium text-text-primary truncate">filename.xlsx</span>
                  <span class="text-xs text-text-muted">۷۵٪</span>
                </div>
                <div class="h-1.5 bg-border-light rounded-full overflow-hidden">
                  <div class="h-full bg-primary rounded-full transition-all duration-300" style="width: 75%"></div>
                </div>
              </div>
              <button class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-500 rounded">
                <i class="fa-solid fa-times"></i>
              </button>
            </div>
          </div>
          
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3">
          <button 
            data-upload-modal-close
            class="px-5 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-sm">
            انصراف
          </button>
          <button class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>آپلود</span>
          </button>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- Preview Modal -->
  <div 
    data-preview-modal
    class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" data-preview-modal-close></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[900px] w-full max-h-[90vh] overflow-hidden flex flex-col">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-border-light flex items-center justify-between flex-shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center" data-preview-icon>
              <i class="fa-solid fa-file-pdf text-red-500"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-text-primary leading-tight" data-preview-title>عنوان فایل</h3>
              <p class="text-xs text-text-muted" data-preview-name>filename.pdf</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <button class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200" title="دانلود">
              <i class="fa-solid fa-download"></i>
            </button>
            <button class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200" title="اشتراک‌گذاری">
              <i class="fa-solid fa-share-nodes"></i>
            </button>
            <button data-preview-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
              <i class="fa-solid fa-times"></i>
            </button>
          </div>
        </div>
        
        <!-- Preview Content -->
        <div class="flex-1 overflow-auto p-6 bg-bg-secondary" data-preview-content>
          <!-- PDF Preview -->
          <div class="preview-pdf hidden bg-white rounded-xl p-8 min-h-[400px] flex items-center justify-center">
            <div class="text-center">
              <i class="fa-solid fa-file-pdf text-6xl text-red-500 mb-4"></i>
              <p class="text-text-secondary">پیش‌نمایش PDF</p>
              <p class="text-sm text-text-muted mt-2">در محیط واقعی، PDF اینجا نمایش داده می‌شود</p>
            </div>
          </div>
          
          <!-- Image Preview -->
          <div class="preview-image hidden">
            <img src="https://via.placeholder.com/800x600/f8fafc/64748b?text=تصویر+نمونه" alt="Preview" class="max-w-full h-auto rounded-xl mx-auto">
          </div>
          
          <!-- Video Preview -->
          <div class="preview-video hidden bg-black rounded-xl overflow-hidden">
            <div class="aspect-video flex items-center justify-center">
              <div class="text-center text-white">
                <i class="fa-solid fa-play-circle text-6xl mb-4 opacity-80"></i>
                <p>پخش ویدیو</p>
              </div>
            </div>
          </div>
          
          <!-- Document Preview (Word, Excel, PowerPoint) -->
          <div class="preview-document hidden bg-white rounded-xl p-8 min-h-[400px] flex items-center justify-center">
            <div class="text-center">
              <i class="fa-solid fa-file-lines text-6xl text-blue-500 mb-4"></i>
              <p class="text-text-secondary">پیش‌نمایش سند</p>
              <p class="text-sm text-text-muted mt-2">برای مشاهده کامل، فایل را دانلود کنید</p>
            </div>
          </div>
        </div>
        
        <!-- File Info Footer -->
        <div class="px-6 py-4 border-t border-border-light flex-shrink-0">
          <div class="flex flex-wrap items-center gap-6 text-sm text-text-muted">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-folder"></i>
              <span>پوشه: <span class="text-text-secondary">فروش</span></span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-hard-drive"></i>
              <span>حجم: <span class="text-text-secondary">۲.۴ MB</span></span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-user"></i>
              <span>آپلود: <span class="text-text-secondary">احمد محمدی</span></span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-calendar"></i>
              <span>تاریخ: <span class="text-text-secondary">۱۴۰۳/۰۹/۰۵</span></span>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- JavaScript -->
  <script src="<?= asset('js/app.js') ?>"></script>
  <script>
    // Documents Module Specific JS
    document.addEventListener('DOMContentLoaded', function() {
      
      // Mobile Sidebar Toggle
      const mobileSidebarToggle = document.querySelector('[data-mobile-sidebar-toggle]');
      const mobileSidebarOverlay = document.querySelector('[data-mobile-sidebar-overlay]');
      const mobileSidebar = document.querySelector('[data-mobile-sidebar]');
      const mobileSidebarClose = document.querySelector('[data-mobile-sidebar-close]');
      
      function openMobileSidebar() {
        mobileSidebarOverlay.classList.remove('hidden');
        mobileSidebar.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
      }
      
      function closeMobileSidebar() {
        mobileSidebarOverlay.classList.add('hidden');
        mobileSidebar.classList.add('translate-x-full');
        document.body.style.overflow = '';
      }
      
      if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', openMobileSidebar);
      }
      if (mobileSidebarOverlay) {
        mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
      }
      if (mobileSidebarClose) {
        mobileSidebarClose.addEventListener('click', closeMobileSidebar);
      }
      
      // Folder Toggle in Sidebar
      document.querySelectorAll('[data-folder-toggle]').forEach(toggle => {
        toggle.addEventListener('click', function() {
          const folderId = this.getAttribute('data-folder-toggle');
          const children = document.querySelector(`[data-folder-children="${folderId}"]`);
          const arrow = this.querySelector('[data-folder-arrow]');
          
          if (children) {
            children.classList.toggle('hidden');
            arrow.classList.toggle('-rotate-90');
          }
        });
      });
      
      // Upload Modal
      const uploadModal = document.querySelector('[data-upload-modal]');
      const uploadModalOpenBtns = document.querySelectorAll('[data-upload-modal-open]');
      const uploadModalCloseBtns = document.querySelectorAll('[data-upload-modal-close]');
      
      function openUploadModal() {
        uploadModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }
      
      function closeUploadModal() {
        uploadModal.classList.add('hidden');
        document.body.style.overflow = '';
      }
      
      uploadModalOpenBtns.forEach(btn => btn.addEventListener('click', openUploadModal));
      uploadModalCloseBtns.forEach(btn => btn.addEventListener('click', closeUploadModal));
      
      // New Folder Modal
      const folderModal = document.querySelector('[data-folder-modal]');
      const folderModalOpenBtns = document.querySelectorAll('[data-folder-modal-open]');
      const folderModalCloseBtns = document.querySelectorAll('[data-folder-modal-close]');
      const folderNameInput = document.querySelector('[data-folder-name-input]');
      const folderParentSelect = document.querySelector('[data-folder-parent-select]');
      const folderPreviewName = document.querySelector('[data-folder-preview-name]');
      const folderPreview = document.querySelector('[data-folder-preview]');
      const folderParentName = document.querySelector('[data-folder-parent-name]');
      
      const folderColors = {
        purple: { main: '#7C3AED', tab: '#A78BFA' },
        pink: { main: '#EC4899', tab: '#F472B6' },
        green: { main: '#10B981', tab: '#34D399' },
        blue: { main: '#3B82F6', tab: '#60A5FA' },
        amber: { main: '#F59E0B', tab: '#FBBF24' },
        slate: { main: '#64748B', tab: '#94A3B8' }
      };
      
      function openFolderModal() {
        folderModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        folderNameInput.value = '';
        folderNameInput.focus();
      }
      
      function closeFolderModal() {
        folderModal.classList.add('hidden');
        document.body.style.overflow = '';
      }
      
      function updateFolderPreview() {
        const name = folderNameInput.value || 'نام پوشه';
        const selectedColor = document.querySelector('input[name="folder-color"]:checked')?.value || 'purple';
        const colors = folderColors[selectedColor];
        
        folderPreviewName.textContent = name;
        folderPreview.innerHTML = `
          <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
            <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="${colors.tab}"></path>
            <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="${colors.main}"></path>
          </svg>
        `;
      }
      
      function updateParentName() {
        const selectedOption = folderParentSelect.options[folderParentSelect.selectedIndex];
        folderParentName.textContent = selectedOption.text;
      }
      
      folderModalOpenBtns.forEach(btn => btn.addEventListener('click', openFolderModal));
      folderModalCloseBtns.forEach(btn => btn.addEventListener('click', closeFolderModal));
      
      folderNameInput?.addEventListener('input', updateFolderPreview);
      document.querySelectorAll('input[name="folder-color"]').forEach(radio => {
        radio.addEventListener('change', updateFolderPreview);
      });
      folderParentSelect?.addEventListener('change', updateParentName);
      
      // Preview Modal
      const previewModal = document.querySelector('[data-preview-modal]');
      const previewModalOpenBtns = document.querySelectorAll('[data-preview-modal-open]');
      const previewModalCloseBtns = document.querySelectorAll('[data-preview-modal-close]');
      
      const fileTypeConfig = {
        pdf: { icon: 'fa-solid fa-file-pdf', color: 'text-red-500', bg: 'bg-red-50', preview: 'preview-pdf' },
        word: { icon: 'fa-solid fa-file-word', color: 'text-blue-600', bg: 'bg-blue-50', preview: 'preview-document' },
        excel: { icon: 'fa-solid fa-file-excel', color: 'text-green-600', bg: 'bg-green-50', preview: 'preview-document' },
        powerpoint: { icon: 'fa-solid fa-file-powerpoint', color: 'text-orange-500', bg: 'bg-orange-50', preview: 'preview-document' },
        image: { icon: 'fa-solid fa-file-image', color: 'text-purple-500', bg: 'bg-purple-50', preview: 'preview-image' },
        video: { icon: 'fa-solid fa-file-video', color: 'text-pink-500', bg: 'bg-pink-50', preview: 'preview-video' }
      };
      
      function openPreviewModal(fileId, fileType, fileName, fileTitle) {
        const config = fileTypeConfig[fileType] || fileTypeConfig.pdf;
        
        // Update modal content
        const iconContainer = previewModal.querySelector('[data-preview-icon]');
        iconContainer.className = `w-10 h-10 ${config.bg} rounded-lg flex items-center justify-center`;
        iconContainer.innerHTML = `<i class="${config.icon} ${config.color}"></i>`;
        
        previewModal.querySelector('[data-preview-title]').textContent = fileTitle;
        previewModal.querySelector('[data-preview-name]').textContent = fileName;
        
        // Show correct preview type
        document.querySelectorAll('[data-preview-content] > div').forEach(el => el.classList.add('hidden'));
        const previewEl = document.querySelector(`.${config.preview}`);
        if (previewEl) previewEl.classList.remove('hidden');
        
        previewModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }
      
      function closePreviewModal() {
        previewModal.classList.add('hidden');
        document.body.style.overflow = '';
      }
      
      previewModalOpenBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const fileId = this.getAttribute('data-file-id');
          const fileType = this.getAttribute('data-file-type');
          const fileName = this.getAttribute('data-file-name');
          const fileTitle = this.getAttribute('data-file-title');
          openPreviewModal(fileId, fileType, fileName, fileTitle);
        });
      });
      
      previewModalCloseBtns.forEach(btn => btn.addEventListener('click', closePreviewModal));
      
      // Dropdowns
      document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.stopPropagation();
          const targetId = this.getAttribute('data-dropdown-toggle');
          const dropdown = document.getElementById(targetId);
          
          // Close other dropdowns
          document.querySelectorAll('[data-dropdown]').forEach(d => {
            if (d.id !== targetId) d.classList.add('hidden');
          });
          
          dropdown.classList.toggle('hidden');
        });
      });
      
      // Close dropdowns on outside click
      document.addEventListener('click', function() {
        document.querySelectorAll('[data-dropdown]').forEach(d => d.classList.add('hidden'));
      });
      
      // Prevent dropdown close when clicking inside
      document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
        dropdown.addEventListener('click', e => e.stopPropagation());
      });
      
      // Convert numbers to Persian
      function toPersianNum(num) {
        const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return String(num).replace(/\d/g, d => persianDigits[d]);
      }
      
      document.querySelectorAll('.persian-num').forEach(el => {
        el.textContent = toPersianNum(el.textContent);
      });
      
    });
  </script>
  
</body>
</html>
