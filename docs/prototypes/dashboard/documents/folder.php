<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت ID پوشه از URL
$folderId = $_GET['id'] ?? 'strategic';

// اطلاعات پوشه‌ها (در پروژه واقعی از دیتابیس می‌آید)
$foldersData = [
    'strategic' => [
        'name' => 'اسناد استراتژیک',
        'icon' => 'fa-solid fa-landmark',
        'color' => 'purple',
        'parent' => null,
        'children' => [
            ['id' => 'hr', 'name' => 'منابع انسانی', 'icon' => 'fa-solid fa-users', 'filesCount' => 45],
            ['id' => 'finance', 'name' => 'مالی', 'icon' => 'fa-solid fa-coins', 'filesCount' => 38],
            ['id' => 'legal', 'name' => 'حقوقی', 'icon' => 'fa-solid fa-scale-balanced', 'filesCount' => 32],
            ['id' => 'management', 'name' => 'مدیریتی', 'icon' => 'fa-solid fa-briefcase', 'filesCount' => 28],
        ]
    ],
    'hr' => [
        'name' => 'منابع انسانی',
        'icon' => 'fa-solid fa-users',
        'color' => 'blue',
        'parent' => ['id' => 'strategic', 'name' => 'اسناد استراتژیک'],
        'children' => []
    ],
    'finance' => [
        'name' => 'مالی',
        'icon' => 'fa-solid fa-coins',
        'color' => 'green',
        'parent' => ['id' => 'strategic', 'name' => 'اسناد استراتژیک'],
        'children' => []
    ],
    'marketing' => [
        'name' => 'بازاریابی و تبلیغات',
        'icon' => 'fa-solid fa-bullhorn',
        'color' => 'pink',
        'parent' => null,
        'children' => [
            ['id' => 'campaigns', 'name' => 'کمپین‌ها', 'icon' => 'fa-solid fa-rocket', 'filesCount' => 56],
            ['id' => 'branding', 'name' => 'برندینگ', 'icon' => 'fa-solid fa-palette', 'filesCount' => 42],
            ['id' => 'social', 'name' => 'شبکه‌های اجتماعی', 'icon' => 'fa-solid fa-share-nodes', 'filesCount' => 89],
        ]
    ],
    'sales' => [
        'name' => 'فروش',
        'icon' => 'fa-solid fa-chart-line',
        'color' => 'green',
        'parent' => null,
        'children' => [
            ['id' => 'contracts', 'name' => 'قراردادها', 'icon' => 'fa-solid fa-file-signature', 'filesCount' => 78],
            ['id' => 'proposals', 'name' => 'پیشنهادات', 'icon' => 'fa-solid fa-file-invoice', 'filesCount' => 45],
            ['id' => 'reports', 'name' => 'گزارشات فروش', 'icon' => 'fa-solid fa-chart-bar', 'filesCount' => 123],
        ]
    ],
    'technical' => [
        'name' => 'فنی و مهندسی',
        'icon' => 'fa-solid fa-gears',
        'color' => 'blue',
        'parent' => null,
        'children' => [
            ['id' => 'specs', 'name' => 'مشخصات فنی', 'icon' => 'fa-solid fa-clipboard-list', 'filesCount' => 67],
            ['id' => 'manuals', 'name' => 'دفترچه راهنما', 'icon' => 'fa-solid fa-book', 'filesCount' => 34],
            ['id' => 'drawings', 'name' => 'نقشه‌ها', 'icon' => 'fa-solid fa-drafting-compass', 'filesCount' => 89],
        ]
    ],
];

// دریافت اطلاعات پوشه فعلی
$currentFolderData = $foldersData[$folderId] ?? $foldersData['strategic'];

// تنظیمات صفحه
$pageTitle = $currentFolderData['name'];
$currentPage = 'all-files';
$currentFolder = $folderId;

// ساخت breadcrumbs
$breadcrumbs = [
    ['label' => 'داشبورد', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت اسناد', 'url' => '/dashboard/documents/index.php'],
];
if ($currentFolderData['parent']) {
    $breadcrumbs[] = ['label' => $currentFolderData['parent']['name'], 'url' => '/dashboard/documents/folder.php?id=' . $currentFolderData['parent']['id']];
}
$breadcrumbs[] = ['label' => $currentFolderData['name']];

// فایل‌های این پوشه (داده نمونه)
$files = [
    [
        'id' => 1,
        'name' => 'گزارش-سالانه-۱۴۰۲.pdf',
        'title' => 'گزارش عملکرد سالانه',
        'type' => 'pdf',
        'size' => '۴.۲ MB',
        'author' => 'مدیر عامل',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=11',
        'date' => '۱۴۰۳/۰۱/۱۵',
        'tags' => ['گزارش', 'سالانه', 'عملکرد'],
        'isTemporary' => false,
        'isFavorite' => true
    ],
    [
        'id' => 2,
        'name' => 'برنامه-استراتژیک-۱۴۰۳-۱۴۰۵.pptx',
        'title' => 'برنامه استراتژیک سه ساله',
        'type' => 'powerpoint',
        'size' => '۱۲.۸ MB',
        'author' => 'هیئت مدیره',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=2',
        'date' => '۱۴۰۲/۱۲/۲۰',
        'tags' => ['استراتژی', 'برنامه‌ریزی'],
        'isTemporary' => false,
        'isFavorite' => true
    ],
    [
        'id' => 3,
        'name' => 'چارت-سازمانی-۱۴۰۳.xlsx',
        'title' => 'چارت سازمانی به‌روز',
        'type' => 'excel',
        'size' => '۸۵۶ KB',
        'author' => 'منابع انسانی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=5',
        'date' => '۱۴۰۳/۰۶/۰۱',
        'tags' => ['سازمان', 'چارت'],
        'isTemporary' => false,
        'isFavorite' => false
    ],
    [
        'id' => 4,
        'name' => 'آیین‌نامه-داخلی.docx',
        'title' => 'آیین‌نامه داخلی شرکت',
        'type' => 'word',
        'size' => '۱.۲ MB',
        'author' => 'حقوقی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=8',
        'date' => '۱۴۰۲/۰۸/۱۰',
        'tags' => ['آیین‌نامه', 'حقوقی', 'قوانین'],
        'isTemporary' => false,
        'isFavorite' => false
    ],
    [
        'id' => 5,
        'name' => 'صورت‌مالی-Q3.xlsx',
        'title' => 'صورت مالی فصل سوم',
        'type' => 'excel',
        'size' => '۲.۱ MB',
        'author' => 'مالی',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=3',
        'date' => '۱۴۰۳/۰۷/۱۵',
        'tags' => ['مالی', 'گزارش', 'فصلی'],
        'isTemporary' => true,
        'isFavorite' => false
    ],
    [
        'id' => 6,
        'name' => 'پروژه‌های-در-دست-اجرا.pdf',
        'title' => 'لیست پروژه‌های فعال',
        'type' => 'pdf',
        'size' => '۳.۵ MB',
        'author' => 'دفتر مدیریت پروژه',
        'authorAvatar' => 'https://i.pravatar.cc/100?img=7',
        'date' => '۱۴۰۳/۰۹/۰۱',
        'tags' => ['پروژه', 'مدیریت'],
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

// رنگ‌های پوشه
$folderColorClasses = [
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-500', 'border' => 'border-purple-200'],
    'pink' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-500', 'border' => 'border-pink-200'],
    'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-500', 'border' => 'border-green-200'],
    'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-500', 'border' => 'border-blue-200'],
    'slate' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'border' => 'border-slate-200'],
    'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-500', 'border' => 'border-amber-200'],
];
$folderColors = $folderColorClasses[$currentFolderData['color']] ?? $folderColorClasses['blue'];

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
          
          <!-- Top Row: Breadcrumb & Actions -->
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
              <!-- Back Button -->
              <?php if ($currentFolderData['parent']): ?>
                <a href="/dashboard/documents/folder.php?id=<?= $currentFolderData['parent']['id'] ?>" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-arrow-right"></i>
                </a>
              <?php else: ?>
                <a href="/dashboard/documents/index.php" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-arrow-right"></i>
                </a>
              <?php endif; ?>
              
              <!-- Folder Info -->
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 <?= $folderColors['bg'] ?> rounded-xl flex items-center justify-center">
                  <i class="<?= $currentFolderData['icon'] ?> <?= $folderColors['text'] ?> text-xl"></i>
                </div>
                <div>
                  <h1 class="text-xl font-bold text-text-primary leading-tight"><?= $currentFolderData['name'] ?></h1>
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
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2">
              <button data-folder-modal-open class="hidden sm:flex items-center gap-2 px-4 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-sm">
                <i class="fa-solid fa-folder-plus"></i>
                <span>پوشه جدید</span>
              </button>
              <button 
                data-upload-modal-open
                class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>آپلود فایل</span>
              </button>
            </div>
          </div>
          
          <!-- Search -->
          <div class="relative max-w-xl">
            <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
            <input 
              type="text" 
              placeholder="جستجو در این پوشه..." 
              class="w-full pr-11 pl-4 py-2.5 border border-border-medium rounded-xl text-sm text-text-primary placeholder:text-text-muted focus:border-primary focus:shadow-focus outline-none transition-all duration-200">
          </div>
          
        </div>
      </header>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Sub-Folders Strip -->
        <?php if (!empty($currentFolderData['children'])): ?>
          <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
              <i class="fa-solid fa-folder text-text-muted"></i>
              <span class="text-sm font-medium text-text-secondary">زیرپوشه‌ها</span>
            </div>
            <div class="flex flex-wrap gap-5">
              <?php foreach ($currentFolderData['children'] as $child): ?>
                <?php
                // رنگ زیرپوشه‌ها بر اساس رنگ پوشه والد
                $parentColor = $currentFolderData['color'] ?? 'blue';
                $subColorClasses = [
                    'purple' => ['main' => '#8B5CF6', 'tab' => '#C4B5FD'],
                    'pink' => ['main' => '#F472B6', 'tab' => '#FBCFE8'],
                    'green' => ['main' => '#34D399', 'tab' => '#A7F3D0'],
                    'blue' => ['main' => '#60A5FA', 'tab' => '#BFDBFE'],
                    'slate' => ['main' => '#94A3B8', 'tab' => '#CBD5E1'],
                    'amber' => ['main' => '#FBBF24', 'tab' => '#FDE68A'],
                ];
                $childColors = $subColorClasses[$parentColor] ?? $subColorClasses['blue'];
                ?>
                <a href="/dashboard/documents/folder.php?id=<?= $child['id'] ?>" class="group block w-[120px]">
                  <div class="relative transition-all duration-200 group-hover:-translate-y-1 group-hover:drop-shadow-lg">
                    <!-- SVG Folder -->
                    <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                      <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $childColors['tab'] ?>"></path>
                      <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $childColors['main'] ?>"></path>
                    </svg>
                    <!-- Folder Content Overlay -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center pt-[28%] px-1 pb-1">
                      <h3 class="text-[10px] sm:text-[11px] font-semibold text-white leading-tight text-center line-clamp-2 drop-shadow-sm"><?= $child['name'] ?></h3>
                      <p class="text-[8px] sm:text-[9px] text-white/80 mt-0.5 persian-num"><?= $child['filesCount'] ?> فایل</p>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- Files Section -->
        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
          
          <!-- Section Header -->
          <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
            <div class="flex items-center gap-3">
              <h2 class="text-lg font-semibold text-text-primary">فایل‌ها</h2>
              <span class="px-2.5 py-1 bg-bg-secondary text-text-muted rounded-lg text-xs font-medium"><?= count($files) ?> فایل</span>
            </div>
            <div class="flex items-center gap-2">
              <!-- Sort -->
              <div class="relative" data-dropdown-container>
                <button 
                  data-dropdown-toggle="sort-dropdown"
                  class="flex items-center gap-2 px-3 py-2 text-sm text-text-secondary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                  <i class="fa-solid fa-arrow-down-wide-short"></i>
                  <span class="hidden sm:inline">مرتب‌سازی</span>
                </button>
                <div 
                  id="sort-dropdown" 
                  data-dropdown
                  class="hidden absolute top-full left-0 mt-2 w-44 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                  <div class="p-2">
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                      <i class="fa-solid fa-calendar w-4"></i> جدیدترین
                    </button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                      <i class="fa-solid fa-font w-4"></i> نام
                    </button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                      <i class="fa-solid fa-hard-drive w-4"></i> حجم
                    </button>
                    <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2">
                      <i class="fa-solid fa-file w-4"></i> نوع
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- View Toggle -->
              <div class="flex items-center border border-border-medium rounded-lg overflow-hidden">
                <button class="w-9 h-9 flex items-center justify-center text-text-muted hover:bg-bg-secondary transition-all duration-200" title="نمای لیستی">
                  <i class="fa-solid fa-list"></i>
                </button>
                <button class="w-9 h-9 flex items-center justify-center bg-primary text-white" title="نمای جدولی">
                  <i class="fa-solid fa-table-cells"></i>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Files Table -->
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
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden md:table-cell">تگ‌ها</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden sm:table-cell">حجم</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">آپلودکننده</th>
                  <th class="text-right px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider hidden lg:table-cell">تاریخ</th>
                  <th class="text-left px-6 py-3 text-xs font-semibold text-text-muted uppercase tracking-wider">عملیات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-border-light">
                <?php foreach ($files as $file): ?>
                  <?php 
                  $typeConfig = $fileTypeConfig[$file['type']] ?? $fileTypeConfig['default'];
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
                          <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-text-primary truncate leading-tight"><?= $file['title'] ?></span>
                            <?php if ($file['isTemporary']): ?>
                              <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded text-[10px] font-medium flex-shrink-0">موقت</span>
                            <?php endif; ?>
                          </div>
                          <p class="text-xs text-text-muted truncate leading-normal"><?= $file['name'] ?></p>
                        </div>
                      </label>
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
                    
                    <!-- Author -->
                    <td class="px-4 py-4 hidden lg:table-cell">
                      <span class="text-sm text-text-secondary"><?= $file['author'] ?></span>
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
                        
                        <!-- Download -->
                        <button 
                          class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-green-500 hover:bg-green-50 rounded-lg transition-all duration-200"
                          title="دانلود">
                          <i class="fa-solid fa-download"></i>
                        </button>
                        
                        <!-- More Actions -->
                        <div class="relative" data-dropdown-container>
                          <button 
                            data-dropdown-toggle="file-actions-<?= $file['id'] ?>"
                            class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200"
                            title="بیشتر">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                          </button>
                          <div 
                            id="file-actions-<?= $file['id'] ?>" 
                            data-dropdown
                            class="hidden absolute top-full left-0 mt-2 w-44 bg-bg-primary border border-border-light rounded-xl shadow-lg overflow-hidden z-50">
                            <div class="p-2">
                              <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-text-secondary">
                                <i class="fa-solid fa-pen w-4"></i> ویرایش
                              </button>
                              <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-text-secondary">
                                <i class="fa-solid fa-folder-open w-4"></i> انتقال
                              </button>
                              <button class="w-full text-right px-3 py-2 text-sm hover:bg-bg-secondary rounded-lg flex items-center gap-2 text-text-secondary">
                                <i class="fa-solid fa-copy w-4"></i> کپی
                              </button>
                              <button class="w-full text-right px-3 py-2 text-sm hover:bg-red-50 rounded-lg flex items-center gap-2 text-red-500">
                                <i class="fa-solid fa-trash w-4"></i> حذف
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          
          <!-- Empty State (if no files) -->
          <?php if (empty($files)): ?>
            <div class="py-16 text-center">
              <div class="w-20 h-20 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-folder-open text-3xl text-text-muted"></i>
              </div>
              <h3 class="text-lg font-semibold text-text-primary mb-2">این پوشه خالی است</h3>
              <p class="text-sm text-text-muted mb-6">فایل‌ها و پوشه‌های خود را اینجا آپلود کنید</p>
              <button 
                data-upload-modal-open
                class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm inline-flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>آپلود فایل</span>
              </button>
            </div>
          <?php endif; ?>
          
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
            <p class="text-sm text-text-muted mt-1">در <span class="text-primary font-medium" data-folder-parent-name><?= $currentFolderData['name'] ?></span></p>
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
              <option value="<?= $folderId ?>" selected><?= $currentFolderData['name'] ?></option>
            </select>
          </div>
          
          <!-- Folder Color -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-text-secondary mb-3">رنگ پوشه</label>
            <div class="flex flex-wrap gap-3">
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="purple" class="hidden peer" <?= $currentFolderData['color'] === 'purple' ? 'checked' : '' ?>>
                <div class="w-10 h-10 rounded-xl bg-purple-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-purple-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="pink" class="hidden peer" <?= $currentFolderData['color'] === 'pink' ? 'checked' : '' ?>>
                <div class="w-10 h-10 rounded-xl bg-pink-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-pink-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="green" class="hidden peer" <?= $currentFolderData['color'] === 'green' ? 'checked' : '' ?>>
                <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-green-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="blue" class="hidden peer" <?= $currentFolderData['color'] === 'blue' ? 'checked' : '' ?>>
                <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-blue-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="amber" class="hidden peer" <?= $currentFolderData['color'] === 'amber' ? 'checked' : '' ?>>
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-amber-500 transition-all">
                  <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="folder-color" value="slate" class="hidden peer" <?= $currentFolderData['color'] === 'slate' ? 'checked' : '' ?>>
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
                <?php 
                $previewColors = [
                    'purple' => ['main' => '#7C3AED', 'tab' => '#A78BFA'],
                    'pink' => ['main' => '#EC4899', 'tab' => '#F472B6'],
                    'green' => ['main' => '#10B981', 'tab' => '#34D399'],
                    'blue' => ['main' => '#3B82F6', 'tab' => '#60A5FA'],
                    'amber' => ['main' => '#F59E0B', 'tab' => '#FBBF24'],
                    'slate' => ['main' => '#64748B', 'tab' => '#94A3B8'],
                ];
                $pColors = $previewColors[$currentFolderData['color']] ?? $previewColors['purple'];
                ?>
                <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                  <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $pColors['tab'] ?>"></path>
                  <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="<?= $pColors['main'] ?>"></path>
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
          <h3 class="text-xl font-semibold text-text-primary leading-snug">آپلود فایل در <?= $currentFolderData['name'] ?></h3>
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
          
          <!-- Tags -->
          <div class="mb-4">
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[120px] px-4 py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                  تگ‌ها
                </label>
                <div class="flex-1 px-4 py-2.5 flex flex-wrap items-center gap-2">
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
                <p class="text-xs text-text-muted mt-0.5">این فایل به صورت موقت ذخیره می‌شود</p>
              </div>
              <i class="fa-solid fa-hourglass-half text-amber-500 mr-auto"></i>
            </label>
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
            <button data-preview-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
              <i class="fa-solid fa-times"></i>
            </button>
          </div>
        </div>
        
        <!-- Preview Content -->
        <div class="flex-1 overflow-auto p-6 bg-bg-secondary" data-preview-content>
          <div class="preview-pdf bg-white rounded-xl p-8 min-h-[400px] flex items-center justify-center">
            <div class="text-center">
              <i class="fa-solid fa-file-pdf text-6xl text-red-500 mb-4"></i>
              <p class="text-text-secondary">پیش‌نمایش فایل</p>
            </div>
          </div>
          <div class="preview-image hidden">
            <img src="https://via.placeholder.com/800x600" class="max-w-full h-auto rounded-xl mx-auto">
          </div>
          <div class="preview-video hidden bg-black rounded-xl overflow-hidden">
            <div class="aspect-video flex items-center justify-center">
              <i class="fa-solid fa-play-circle text-6xl text-white opacity-80"></i>
            </div>
          </div>
          <div class="preview-document hidden bg-white rounded-xl p-8 min-h-[400px] flex items-center justify-center">
            <div class="text-center">
              <i class="fa-solid fa-file-lines text-6xl text-blue-500 mb-4"></i>
              <p class="text-text-secondary">پیش‌نمایش سند</p>
            </div>
          </div>
        </div>
        
      </div>
    </div>
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
      
      // Upload Modal
      const uploadModal = document.querySelector('[data-upload-modal]');
      document.querySelectorAll('[data-upload-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
          uploadModal?.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        });
      });
      document.querySelectorAll('[data-upload-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
          uploadModal?.classList.add('hidden');
          document.body.style.overflow = '';
        });
      });
      
      // New Folder Modal
      const folderModal = document.querySelector('[data-folder-modal]');
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
      
      function updateFolderPreview() {
        const name = folderNameInput?.value || 'نام پوشه';
        const selectedColor = document.querySelector('input[name="folder-color"]:checked')?.value || 'purple';
        const colors = folderColors[selectedColor];
        
        if (folderPreviewName) folderPreviewName.textContent = name;
        if (folderPreview) {
          folderPreview.innerHTML = `
            <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
              <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="${colors.tab}"></path>
              <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="${colors.main}"></path>
            </svg>
          `;
        }
      }
      
      function updateParentName() {
        if (folderParentSelect && folderParentName) {
          const selectedOption = folderParentSelect.options[folderParentSelect.selectedIndex];
          folderParentName.textContent = selectedOption.text;
        }
      }
      
      document.querySelectorAll('[data-folder-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
          folderModal?.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
          if (folderNameInput) {
            folderNameInput.value = '';
            folderNameInput.focus();
          }
        });
      });
      
      document.querySelectorAll('[data-folder-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
          folderModal?.classList.add('hidden');
          document.body.style.overflow = '';
        });
      });
      
      folderNameInput?.addEventListener('input', updateFolderPreview);
      document.querySelectorAll('input[name="folder-color"]').forEach(radio => {
        radio.addEventListener('change', updateFolderPreview);
      });
      folderParentSelect?.addEventListener('change', updateParentName);
      
      // Preview Modal
      const previewModal = document.querySelector('[data-preview-modal]');
      const fileTypeConfig = {
        pdf: { icon: 'fa-solid fa-file-pdf', color: 'text-red-500', bg: 'bg-red-50' },
        word: { icon: 'fa-solid fa-file-word', color: 'text-blue-600', bg: 'bg-blue-50' },
        excel: { icon: 'fa-solid fa-file-excel', color: 'text-green-600', bg: 'bg-green-50' },
        powerpoint: { icon: 'fa-solid fa-file-powerpoint', color: 'text-orange-500', bg: 'bg-orange-50' },
        image: { icon: 'fa-solid fa-file-image', color: 'text-purple-500', bg: 'bg-purple-50' },
        video: { icon: 'fa-solid fa-file-video', color: 'text-pink-500', bg: 'bg-pink-50' }
      };
      
      document.querySelectorAll('[data-preview-modal-open]').forEach(btn => {
        btn.addEventListener('click', function() {
          const fileType = this.getAttribute('data-file-type');
          const fileName = this.getAttribute('data-file-name');
          const fileTitle = this.getAttribute('data-file-title');
          const config = fileTypeConfig[fileType] || fileTypeConfig.pdf;
          
          const iconContainer = previewModal?.querySelector('[data-preview-icon]');
          if (iconContainer) {
            iconContainer.className = `w-10 h-10 ${config.bg} rounded-lg flex items-center justify-center`;
            iconContainer.innerHTML = `<i class="${config.icon} ${config.color}"></i>`;
          }
          
          const titleEl = previewModal?.querySelector('[data-preview-title]');
          const nameEl = previewModal?.querySelector('[data-preview-name]');
          if (titleEl) titleEl.textContent = fileTitle;
          if (nameEl) nameEl.textContent = fileName;
          
          previewModal?.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        });
      });
      
      document.querySelectorAll('[data-preview-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
          previewModal?.classList.add('hidden');
          document.body.style.overflow = '';
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
