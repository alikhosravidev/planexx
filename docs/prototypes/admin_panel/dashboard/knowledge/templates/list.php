<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'مدیریت قالب‌های تجربه';
$currentPage = 'knowledge-base';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'افزودن قالب جدید', 'url' => '/dashboard/knowledge/templates/create.php', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'پایگاه تجربه سازمانی', 'url' => '/dashboard/knowledge/index.php'],
    ['label' => 'مدیریت قالب‌های تجربه'],
];

// داده‌های نمونه قالب‌ها (در پروژه واقعی از دیتابیس)
$templates = [
    [
        'id'                  => 1,
        'name'                => 'تجربه قراردادی',
        'department'          => 'مالی',
        'custom_fields_count' => 5,
        'experiences_count'   => 28,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۷/۱۵',
        'updated_at'          => '۱۴۰۳/۰۸/۲۰',
    ],
    [
        'id'                  => 2,
        'name'                => 'بهبود فرآیند تولید',
        'department'          => 'تولید',
        'custom_fields_count' => 7,
        'experiences_count'   => 42,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۶/۱۰',
        'updated_at'          => '۱۴۰۳/۰۹/۰۱',
    ],
    [
        'id'                  => 3,
        'name'                => 'استخدام و جذب نیرو',
        'department'          => 'منابع انسانی',
        'custom_fields_count' => 4,
        'experiences_count'   => 19,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۵/۲۵',
        'updated_at'          => '۱۴۰۳/۰۸/۱۵',
    ],
    [
        'id'                  => 4,
        'name'                => 'استراتژی فروش',
        'department'          => 'فروش',
        'custom_fields_count' => 6,
        'experiences_count'   => 35,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۴/۱۲',
        'updated_at'          => '۱۴۰۳/۰۸/۲۸',
    ],
    [
        'id'                  => 5,
        'name'                => 'توسعه نرم‌افزار',
        'department'          => 'فنی',
        'custom_fields_count' => 8,
        'experiences_count'   => 53,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۳/۰۵',
        'updated_at'          => '۱۴۰۳/۰۹/۰۵',
    ],
    [
        'id'                  => 6,
        'name'                => 'مدیریت پروژه',
        'department'          => 'مدیریت',
        'custom_fields_count' => 5,
        'experiences_count'   => 22,
        'is_active'           => true,
        'created_at'          => '۱۴۰۳/۰۲/۲۰',
        'updated_at'          => '۱۴۰۳/۰۸/۱۰',
    ],
    [
        'id'                  => 7,
        'name'                => 'تجربیات مالی عمومی',
        'department'          => 'مالی',
        'custom_fields_count' => 3,
        'experiences_count'   => 12,
        'is_active'           => false,
        'created_at'          => '۱۴۰۳/۰۱/۱۵',
        'updated_at'          => '۱۴۰۳/۰۷/۰۵',
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
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Search -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    <i class="fa-solid fa-search"></i>
                  </label>
                  <input type="text" 
                         class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                         placeholder="جستجوی قالب..."
                         data-search=".template-row">
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
            
            <!-- Status Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    وضعیت
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="status">
                    <option value="">همه</option>
                    <option value="active">فعال</option>
                    <option value="inactive">غیرفعال</option>
                  </select>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        
        <!-- Templates Table -->
        <div class="bg-bg-primary border border-border-light rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-bg-secondary border-b border-border-light">
                <tr>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">نام قالب</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">دپارتمان</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">فیلدهای سفارشی</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">تعداد تجربیات</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">وضعیت</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">آخرین بروزرسانی</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary leading-normal">عملیات</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($templates as $template): ?>
                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200 template-row" 
                    data-department="<?= $template['department'] ?>"
                    data-status="<?= $template['is_active'] ? 'active' : 'inactive' ?>">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-lines text-teal-600"></i>
                      </div>
                      <div>
                        <p class="text-base text-text-primary font-medium leading-normal"><?= $template['name'] ?></p>
                        <p class="text-xs text-text-muted leading-normal">ایجاد: <?= $template['created_at'] ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-sitemap text-[10px]"></i>
                      <?= $template['department'] ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal">
                    <?= $template['custom_fields_count'] ?> فیلد
                  </td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-lightbulb text-[10px]"></i>
                      <?= $template['experiences_count'] ?> تجربه
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <?php if ($template['is_active']): ?>
                      <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        فعال
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                        <i class="fa-solid fa-circle text-[6px]"></i>
                        غیرفعال
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4 text-sm text-text-secondary leading-normal">
                    <?= $template['updated_at'] ?>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <a href="/dashboard/knowledge/templates/edit.php?id=<?= $template['id'] ?>" 
                         class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-teal-600 hover:bg-teal-50 rounded transition-all duration-200"
                         title="ویرایش">
                        <i class="fa-solid fa-pen text-sm"></i>
                      </a>
                      <a href="/dashboard/knowledge/templates/view.php?id=<?= $template['id'] ?>" 
                         class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-blue-600 hover:bg-blue-50 rounded transition-all duration-200"
                         title="مشاهده جزئیات">
                        <i class="fa-solid fa-eye text-sm"></i>
                      </a>
                      <button 
                         class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                         title="حذف"
                         onclick="if(confirm('آیا از حذف این قالب اطمینان دارید؟')) { /* حذف */ }">
                        <i class="fa-solid fa-trash text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </main>
  </div>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  // فیلتر دپارتمان و وضعیت
  document.querySelectorAll('[data-filter]').forEach(select => {
    select.addEventListener('change', function() {
      const departmentFilter = document.querySelector('[data-filter="department"]').value.toLowerCase();
      const statusFilter = document.querySelector('[data-filter="status"]').value.toLowerCase();
      
      document.querySelectorAll('.template-row').forEach(row => {
        const department = row.dataset.department.toLowerCase();
        const status = row.dataset.status.toLowerCase();
        
        const matchDepartment = !departmentFilter || department.includes(departmentFilter);
        const matchStatus = !statusFilter || status === statusFilter;
        
        row.style.display = (matchDepartment && matchStatus) ? '' : 'none';
      });
    });
  });
  </script>
  
</body>
</html>


