<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'مدیریت تجربیات سازمانی';
$currentPage = 'knowledge-base';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'ثبت تجربه جدید', 'url' => '/dashboard/knowledge/experiences/create.php', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'پایگاه تجربه سازمانی', 'url' => '/dashboard/knowledge/index.php'],
    ['label' => 'مدیریت تجربیات'],
];

// داده‌های نمونه تجربیات (در پروژه واقعی از دیتابیس)
$experiences = [
    [
        'id'               => 1,
        'title'            => 'بهینه‌سازی فرآیند قراردادهای مالی',
        'summary'          => 'با استفاده از اتوماسیون در فرآیند قراردادها، زمان پردازش را ۴۰٪ کاهش دادیم',
        'department'       => 'مالی',
        'department_color' => 'green',
        'template'         => 'تجربه قراردادی',
        'author'           => 'احمد باقری',
        'date'             => '۱۴۰۳/۰۹/۰۵',
        'status'           => 'published',
        'views'            => 145,
        'rating'           => 4.8,
        'attachments'      => 5,
    ],
    [
        'id'               => 2,
        'title'            => 'کاهش زمان تولید محصول X از ۴ ساعت به ۲.۵ ساعت',
        'summary'          => 'با تغییر چیدمان خط تولید و بهینه‌سازی مراحل، بهره‌وری ۳۷٪ افزایش یافت',
        'department'       => 'تولید',
        'department_color' => 'slate',
        'template'         => 'بهبود فرآیند تولید',
        'author'           => 'رضا صانعی',
        'date'             => '۱۴۰۳/۰۹/۰۴',
        'status'           => 'published',
        'views'            => 203,
        'rating'           => 4.9,
        'attachments'      => 3,
    ],
    [
        'id'               => 3,
        'title'            => 'راهکار جدید جذب نیروی متخصص در حوزه IT',
        'summary'          => 'با همکاری با دانشگاه‌ها و برگزاری رویدادهای تخصصی، استخدام را ۵۰٪ سریع‌تر کردیم',
        'department'       => 'منابع انسانی',
        'department_color' => 'pink',
        'template'         => 'استخدام و جذب نیرو',
        'author'           => 'مریم نوری',
        'date'             => '۱۴۰۳/۰۹/۰۳',
        'status'           => 'draft',
        'views'            => 67,
        'rating'           => 4.5,
        'attachments'      => 2,
    ],
    [
        'id'               => 4,
        'title'            => 'افزایش ۶۰٪ فروش محصولات دیجیتال با تغییر استراتژی بازاریابی',
        'summary'          => 'تمرکز روی شبکه‌های اجتماعی و اینفلوئنسر مارکتینگ نتایج فوق‌العاده‌ای داشت',
        'department'       => 'فروش',
        'department_color' => 'orange',
        'template'         => 'استراتژی فروش',
        'author'           => 'فاطمه محمدی',
        'date'             => '۱۴۰۳/۰۹/۰۲',
        'status'           => 'published',
        'views'            => 312,
        'rating'           => 4.7,
        'attachments'      => 8,
    ],
    [
        'id'               => 5,
        'title'            => 'پیاده‌سازی CI/CD و کاهش زمان Deploy از ۲ ساعت به ۱۰ دقیقه',
        'summary'          => 'با استفاده از Jenkins و Docker، فرآیند deployment را کاملاً خودکار کردیم',
        'department'       => 'فنی',
        'department_color' => 'blue',
        'template'         => 'توسعه نرم‌افزار',
        'author'           => 'سارا قاسمی',
        'date'             => '۱۴۰۳/۰۹/۰۱',
        'status'           => 'published',
        'views'            => 278,
        'rating'           => 5.0,
        'attachments'      => 12,
    ],
    [
        'id'               => 6,
        'title'            => 'بهبود فرآیند onboarding کارکنان جدید',
        'summary'          => 'با ایجاد چک‌لیست دیجیتال و منتورینگ، رضایت کارکنان جدید ۴۵٪ افزایش یافت',
        'department'       => 'منابع انسانی',
        'department_color' => 'pink',
        'template'         => 'استخدام و جذب نیرو',
        'author'           => 'مریم نوری',
        'date'             => '۱۴۰۳/۰۸/۲۸',
        'status'           => 'published',
        'views'            => 189,
        'rating'           => 4.6,
        'attachments'      => 4,
    ],
    [
        'id'               => 7,
        'title'            => 'کاهش ضایعات تولید با استفاده از IoT',
        'summary'          => 'نصب سنسورها و مانیتورینگ لحظه‌ای باعث کاهش ۲۵٪ ضایعات شد',
        'department'       => 'تولید',
        'department_color' => 'slate',
        'template'         => 'بهبود فرآیند تولید',
        'author'           => 'رضا صانعی',
        'date'             => '۱۴۰۳/۰۸/۲۵',
        'status'           => 'published',
        'views'            => 156,
        'rating'           => 4.4,
        'attachments'      => 6,
    ],
    [
        'id'               => 8,
        'title'            => 'مدیریت بحران کرونا در سازمان',
        'summary'          => 'استراتژی دورکاری و ابزارهای همکاری آنلاین را با موفقیت پیاده‌سازی کردیم',
        'department'       => 'مدیریت',
        'department_color' => 'purple',
        'template'         => 'مدیریت پروژه',
        'author'           => 'محمد رضایی',
        'date'             => '۱۴۰۳/۰۸/۲۰',
        'status'           => 'archived',
        'views'            => 423,
        'rating'           => 4.9,
        'attachments'      => 15,
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('knowledge-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Filters & Search -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Search -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    <i class="fa-solid fa-search"></i>
                  </label>
                  <input type="text" 
                         class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                         placeholder="جستجوی تجربه..."
                         data-search=".experience-row">
                </div>
              </div>
            </div>
            
            <!-- Department Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    دپارتمان
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="department">
                    <option value="">همه دپارتمان‌ها</option>
                    <option value="مالی">مالی</option>
                    <option value="تولید">تولید</option>
                    <option value="منابع انسانی">منابع انسانی</option>
                    <option value="فروش">فروش</option>
                    <option value="فنی">فنی</option>
                    <option value="مدیریت">مدیریت</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Template Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    قالب
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="template">
                    <option value="">همه قالب‌ها</option>
                    <option value="تجربه قراردادی">تجربه قراردادی</option>
                    <option value="بهبود فرآیند تولید">بهبود فرآیند تولید</option>
                    <option value="استخدام و جذب نیرو">استخدام و جذب نیرو</option>
                    <option value="استراتژی فروش">استراتژی فروش</option>
                    <option value="توسعه نرم‌افزار">توسعه نرم‌افزار</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Status Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    وضعیت
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="status">
                    <option value="">همه</option>
                    <option value="published">منتشر شده</option>
                    <option value="draft">پیش‌نویس</option>
                    <option value="archived">آرشیو شده</option>
                  </select>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- Experiences List -->
        <div class="grid grid-cols-1 gap-4">
          <?php foreach ($experiences as $exp): ?>
          <div class="bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md transition-all duration-200 experience-row"
               data-department="<?= $exp['department'] ?>"
               data-template="<?= $exp['template'] ?>"
               data-status="<?= $exp['status'] ?>">
            
            <!-- Header: Title & Actions -->
            <div class="flex items-start justify-between gap-4 mb-3">
              <h3 class="flex-1 text-lg font-semibold text-text-primary leading-snug"><?= $exp['title'] ?></h3>
              
              <!-- Actions -->
              <div class="flex items-center gap-2">
                <a href="/dashboard/knowledge/experiences/view.php?id=<?= $exp['id'] ?>" 
                   class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-blue-600 hover:bg-blue-50 rounded transition-all duration-200"
                   title="مشاهده جزئیات">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="/dashboard/knowledge/experiences/edit.php?id=<?= $exp['id'] ?>" 
                   class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-teal-600 hover:bg-teal-50 rounded transition-all duration-200"
                   title="ویرایش">
                  <i class="fa-solid fa-pen"></i>
                </a>
                <button 
                   class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                   title="حذف"
                   onclick="if(confirm('آیا از حذف این تجربه اطمینان دارید؟')) { /* حذف */ }">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
            
            <!-- Summary -->
            <p class="text-base text-text-secondary leading-relaxed mb-4"><?= $exp['summary'] ?></p>
            
            <!-- Badges & Meta Info Row -->
            <div class="flex items-center justify-between gap-6">
              
              <!-- Right Side: Badges -->
              <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 bg-<?= $exp['department_color'] ?>-50 text-<?= $exp['department_color'] ?>-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                  <i class="fa-solid fa-sitemap text-[10px]"></i>
                  <?= $exp['department'] ?>
                </span>
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                  <i class="fa-solid fa-file-lines text-[10px]"></i>
                  <?= $exp['template'] ?>
                </span>
                <?php if ($exp['status'] === 'published'): ?>
                  <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    منتشر شده
                  </span>
                <?php elseif ($exp['status'] === 'draft'): ?>
                  <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    پیش‌نویس
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    آرشیو شده
                  </span>
                <?php endif; ?>
              </div>
              
              <!-- Left Side: Meta Info -->
              <div class="flex flex-wrap items-center gap-4 text-sm text-text-secondary leading-normal">
                <span class="inline-flex items-center gap-1.5">
                  <i class="fa-solid fa-user text-xs"></i>
                  <?= $exp['author'] ?>
                </span>
                <span class="inline-flex items-center gap-1.5">
                  <i class="fa-solid fa-calendar text-xs"></i>
                  <?= $exp['date'] ?>
                </span>
                <span class="inline-flex items-center gap-1.5">
                  <i class="fa-solid fa-eye text-xs"></i>
                  <?= $exp['views'] ?> بازدید
                </span>
                <span class="inline-flex items-center gap-1.5">
                  <i class="fa-solid fa-star text-teal-500 text-xs"></i>
                  <?= $exp['rating'] ?>
                </span>
                <span class="inline-flex items-center gap-1.5">
                  <i class="fa-solid fa-paperclip text-xs"></i>
                  <?= $exp['attachments'] ?>
                </span>
              </div>
              
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        
      </div>
    </main>
  </div>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  // فیلترهای چندگانه
  document.querySelectorAll('[data-filter]').forEach(select => {
    select.addEventListener('change', function() {
      const departmentFilter = document.querySelector('[data-filter="department"]').value.toLowerCase();
      const templateFilter = document.querySelector('[data-filter="template"]').value.toLowerCase();
      const statusFilter = document.querySelector('[data-filter="status"]').value.toLowerCase();
      
      document.querySelectorAll('.experience-row').forEach(row => {
        const department = row.dataset.department.toLowerCase();
        const template = row.dataset.template.toLowerCase();
        const status = row.dataset.status.toLowerCase();
        
        const matchDepartment = !departmentFilter || department.includes(departmentFilter);
        const matchTemplate = !templateFilter || template.includes(templateFilter);
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = (matchDepartment && matchTemplate && matchStatus) ? '' : 'none';
      });
    });
  });
  </script>
  
</body>
</html>


