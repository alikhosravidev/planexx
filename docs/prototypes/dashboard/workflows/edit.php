<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت شناسه فرایند از URL
$workflowId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// داده‌های نمونه فرایند (در پروژه واقعی از دیتابیس)
$workflow = [
    'id' => 1,
    'name' => 'اینسپکشن',
    'slug' => 'inception',
    'description' => 'فرایند بررسی و ارزیابی اولیه مشتریان جدید',
    'department_id' => 1,
    'workflow_owner_id' => 1,
    'allowed_roles' => [1, 2, 3],
    'is_active' => true,
    'states' => [
        [
            'id' => 1,
            'name' => 'آزمون ورودی',
            'slug' => 'entrance-exam',
            'description' => 'مرحله ارزیابی اولیه',
            'color' => '#E0F2F1',
            'order' => 1,
            'position' => 'start',
            'default_assignee_id' => null,
        ],
        [
            'id' => 2,
            'name' => 'مشاوره خصوصی',
            'slug' => 'private-consultation',
            'description' => 'جلسه مشاوره با مشتری',
            'color' => '#E3F2FD',
            'order' => 2,
            'position' => 'middle',
            'default_assignee_id' => 1,
        ],
        [
            'id' => 3,
            'name' => 'منتظر پرداخت',
            'slug' => 'waiting-payment',
            'description' => '',
            'color' => '#FFF3E0',
            'order' => 3,
            'position' => 'middle',
            'default_assignee_id' => 2,
        ],
        [
            'id' => 4,
            'name' => 'پیش پرداخت شده',
            'slug' => 'prepaid',
            'description' => '',
            'color' => '#E8F5E9',
            'order' => 4,
            'position' => 'middle',
            'default_assignee_id' => null,
        ],
        [
            'id' => 5,
            'name' => 'تکمیل شده',
            'slug' => 'completed',
            'description' => 'فرایند با موفقیت تکمیل شد',
            'color' => '#B2DFDB',
            'order' => 5,
            'position' => 'final-success',
            'default_assignee_id' => null,
        ],
    ],
];

// تنظیمات صفحه
$pageTitle = 'ویرایش فرایند: ' . $workflow['name'];
$currentPage = 'workflow';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/workflows/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline']
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'لیست فرایندها', 'url' => '/dashboard/workflows/list.php'],
    ['label' => 'ویرایش: ' . $workflow['name']],
];

// دپارتمان‌ها
$departments = [
    ['id' => 1, 'name' => 'فروش'],
    ['id' => 2, 'name' => 'مالی'],
    ['id' => 3, 'name' => 'منابع انسانی'],
    ['id' => 4, 'name' => 'فنی'],
    ['id' => 5, 'name' => 'تولید'],
    ['id' => 6, 'name' => 'عمومی'],
];

// کاربران
$users = [
    ['id' => 1, 'name' => 'علی احمدی', 'department' => 'فروش'],
    ['id' => 2, 'name' => 'مریم رضایی', 'department' => 'مالی'],
    ['id' => 3, 'name' => 'سارا محمدی', 'department' => 'منابع انسانی'],
    ['id' => 4, 'name' => 'رضا کریمی', 'department' => 'فنی'],
    ['id' => 5, 'name' => 'فاطمه نوری', 'department' => 'تولید'],
];

// نقش‌ها
$roles = [
    ['id' => 1, 'name' => 'مدیر سیستم'],
    ['id' => 2, 'name' => 'مدیر فروش'],
    ['id' => 3, 'name' => 'کارشناس فروش'],
    ['id' => 4, 'name' => 'مدیر مالی'],
    ['id' => 5, 'name' => 'حسابدار'],
    ['id' => 6, 'name' => 'کارشناس HR'],
];

// رنگ‌های پیشنهادی - 20 رنگ پرکاربرد
$stateColors = [
    '#E3F2FD', // آبی روشن - شروع/اطلاعات
    '#BBDEFB', // آبی - در حال انجام
    '#E8F5E9', // سبز روشن - موفقیت
    '#C8E6C9', // سبز - تایید شده
    '#FFF3E0', // نارنجی روشن - انتظار
    '#FFE0B2', // نارنجی - هشدار
    '#FFF8E1', // زرد روشن - بررسی
    '#FFECB3', // زرد - توجه
    '#F3E5F5', // بنفش روشن - خاص
    '#E1BEE7', // بنفش - VIP
    '#E0F2F1', // فیروزه‌ای روشن - جدید
    '#B2DFDB', // فیروزه‌ای - فعال
    '#FFEBEE', // قرمز روشن - مهم
    '#FFCDD2', // قرمز - خطر/رد
    '#FCE4EC', // صورتی روشن - پیگیری
    '#F8BBD9', // صورتی - اولویت
    '#E8EAF6', // نیلی روشن - برنامه‌ریزی
    '#C5CAE9', // نیلی - آرشیو
    '#ECEFF1', // خاکستری روشن - غیرفعال
    '#CFD8DC', // خاکستری - بسته
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST" id="workflowForm">
        <input type="hidden" name="id" value="<?= $workflow['id'] ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- فرم اصلی -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- اطلاعات پایه فرایند -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-start gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fa-solid fa-info text-white"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-text-primary leading-snug">اطلاعات پایه فرایند</h2>
                  <p class="text-sm text-text-secondary leading-normal mt-1">مشخصات اصلی فرایند را ویرایش کنید</p>
                </div>
              </div>
              
              <div class="space-y-4">
                
                <!-- نام فرایند و شناسه یکتا -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- نام فرایند -->
                  <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch">
                      <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                        نام فرایند <span class="text-red-500 mr-1">*</span>
                      </label>
                      <input type="text" name="name" required
                             class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                             value="<?= htmlspecialchars($workflow['name']) ?>">
                    </div>
                  </div>
                  
                  <!-- شناسه یکتا (Slug) -->
                  <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch flex-row-reverse">
                      <label class="bg-bg-label border-r border-border-light min-w-[80px] px-lg py-3.5 text-sm text-text-secondary flex items-center justify-start leading-normal">
                        Slug
                      </label>
                      <input type="text" name="slug" id="slugInput"
                             class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal font-mono text-left"
                             value="<?= htmlspecialchars($workflow['slug']) ?>"
                             pattern="[a-z0-9\-]+"
                             dir="ltr">
                    </div>
                  </div>
                </div>
                
                <!-- توضیحات -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3 text-sm text-text-secondary leading-normal">
                      توضیحات
                    </label>
                    <textarea rows="2" name="description"
                              class="flex-1 px-lg py-3 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"><?= htmlspecialchars($workflow['description']) ?></textarea>
                  </div>
                </div>
                
                <!-- دپارتمان و مدیر فرایند -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- دپارتمان -->
                  <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch">
                      <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                        دپارتمان
                      </label>
                      <select name="department_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                        <option value="">انتخاب کنید</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>" <?= $workflow['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= $dept['name'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  
                  <!-- مدیر فرایند -->
                  <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                    <div class="flex items-stretch">
                      <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                        مدیر
                      </label>
                      <select name="workflow_owner_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                        <option value="">انتخاب کنید</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= $workflow['workflow_owner_id'] == $user['id'] ? 'selected' : '' ?>><?= $user['name'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                
                <!-- نقش‌های مجاز -->
                <div class="border border-border-medium rounded-xl p-4">
                  <label class="block text-sm text-text-secondary mb-3 leading-normal">نقش‌های مجاز برای دسترسی</label>
                  <div class="flex flex-wrap gap-2">
                    <?php foreach ($roles as $role): 
                      $isChecked = in_array($role['id'], $workflow['allowed_roles']);
                    ?>
                    <label class="role-badge inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border-2 cursor-pointer transition-all duration-200 <?= $isChecked ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-gray-50 border-gray-200 text-text-secondary hover:border-gray-300' ?>">
                      <input type="checkbox" name="allowed_roles[]" value="<?= $role['id'] ?>" 
                             <?= $isChecked ? 'checked' : '' ?>
                             class="hidden role-checkbox">
                      <i class="role-icon fa-solid <?= $isChecked ? 'fa-check-circle text-indigo-500' : 'fa-circle text-gray-300' ?> text-xs"></i>
                      <span class="text-sm font-medium leading-normal"><?= $role['name'] ?></span>
                    </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                
              </div>
            </div>
            
            <!-- مراحل فرایند -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-start justify-between gap-3 mb-6">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-layer-group text-white"></i>
                  </div>
                  <div>
                    <h2 class="text-lg font-semibold text-text-primary leading-snug">مراحل فرایند</h2>
                    <p class="text-sm text-text-secondary leading-normal mt-1">مراحل مختلف این فرایند را مدیریت کنید</p>
                  </div>
                </div>
                <button type="button" id="addStateBtn"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 text-sm leading-normal">
                  <i class="fa-solid fa-plus"></i>
                  <span>افزودن مرحله</span>
                </button>
              </div>
              
              <!-- States Container -->
              <div id="statesContainer" class="space-y-4">
                <!-- مراحل موجود -->
                <?php foreach ($workflow['states'] as $index => $state): ?>
                <div class="state-item bg-bg-secondary border border-border-light rounded-xl p-4 relative" data-state-id="<?= $state['id'] ?>">
                  <!-- Drag Handle -->
                  <div class="absolute right-3 top-3 cursor-move state-drag-handle text-text-muted hover:text-text-primary">
                    <i class="fa-solid fa-grip-vertical"></i>
                  </div>
                  
                  <!-- Delete Button -->
                  <button type="button" class="absolute left-3 top-3 text-text-muted hover:text-red-600 transition-colors delete-state-btn">
                    <i class="fa-solid fa-times"></i>
                  </button>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    
                    <!-- نام مرحله -->
                    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                      <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                          نام مرحله <span class="text-red-500 mr-1">*</span>
                        </label>
                        <input type="text" name="states[<?= $index ?>][name]" required
                               class="state-name-input flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white leading-normal"
                               value="<?= htmlspecialchars($state['name']) ?>">
                      </div>
                    </div>
                    
                    <!-- شناسه مرحله -->
                    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                      <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                          شناسه
                        </label>
                        <input type="text" name="states[<?= $index ?>][slug]"
                               class="state-slug-input flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white leading-normal font-mono"
                               value="<?= htmlspecialchars($state['slug']) ?>"
                               pattern="[a-z0-9\-]+"
                               dir="ltr">
                      </div>
                    </div>
                    
                    <!-- رنگ مرحله - تمام عرض -->
                    <div class="md:col-span-2 border border-border-medium rounded-xl overflow-hidden">
                      <div class="flex items-center">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal flex-shrink-0">
                          رنگ
                        </label>
                        <div class="flex-1 flex items-center gap-1.5 px-3 py-2 bg-white overflow-x-auto">
                          <input type="hidden" name="states[<?= $index ?>][color]" value="<?= $state['color'] ?>" class="state-color-input">
                          <?php foreach ($stateColors as $clr): ?>
                          <button type="button" 
                                  class="color-square w-6 h-6 rounded flex-shrink-0 border-2 transition-all duration-150 hover:scale-110 <?= $clr === $state['color'] ? 'border-indigo-600 ring-2 ring-indigo-200' : 'border-transparent hover:border-gray-300' ?>"
                                  style="background-color: <?= $clr ?>"
                                  data-color="<?= $clr ?>"
                                  title="<?= $clr ?>"></button>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                    
                    <!-- موقعیت مرحله -->
                    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                      <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                          موقعیت
                        </label>
                        <select name="states[<?= $index ?>][position]" class="state-position-select flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white cursor-pointer leading-normal">
                          <option value="start" <?= $state['position'] == 'start' ? 'selected' : '' ?>>نقطه شروع</option>
                          <option value="middle" <?= $state['position'] == 'middle' ? 'selected' : '' ?>>میانی</option>
                          <option value="final-success" <?= $state['position'] == 'final-success' ? 'selected' : '' ?>>پایان موفق</option>
                          <option value="final-failed" <?= $state['position'] == 'final-failed' ? 'selected' : '' ?>>پایان ناموفق</option>
                          <option value="final-closed" <?= $state['position'] == 'final-closed' ? 'selected' : '' ?>>بسته شده</option>
                        </select>
                      </div>
                    </div>
                    
                    <!-- مسئول پیش‌فرض -->
                    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                      <div class="flex items-stretch">
                        <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                          مسئول پیش‌فرض
                        </label>
                        <select name="states[<?= $index ?>][default_assignee_id]" class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white cursor-pointer leading-normal">
                          <option value="">بدون مسئول</option>
                          <?php foreach ($users as $user): ?>
                          <option value="<?= $user['id'] ?>" <?= $state['default_assignee_id'] == $user['id'] ? 'selected' : '' ?>><?= $user['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    
                    <!-- توضیحات مرحله -->
                    <div class="md:col-span-2 border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                      <div class="flex">
                        <label class="bg-bg-label border-l border-border-light min-w-[120px] px-3 py-3 text-sm text-text-secondary leading-normal">
                          توضیحات
                        </label>
                        <textarea rows="2" name="states[<?= $index ?>][description]"
                                  class="flex-1 px-3 py-3 text-base text-text-primary outline-none resize-none bg-white leading-relaxed"><?= htmlspecialchars($state['description']) ?></textarea>
                      </div>
                    </div>
                    
                  </div>
                  
                  <!-- Hidden Fields -->
                  <input type="hidden" name="states[<?= $index ?>][id]" value="<?= $state['id'] ?>">
                  <input type="hidden" name="states[<?= $index ?>][order]" class="state-order-input" value="<?= $state['order'] ?>">
                </div>
                <?php endforeach; ?>
              </div>
              
              <!-- Empty State -->
              <div id="emptyStatesMessage" class="text-center py-12 border-2 border-dashed border-border-medium rounded-xl hidden">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                  <i class="fa-solid fa-layer-group text-indigo-600 text-2xl"></i>
                </div>
                <p class="text-text-secondary leading-relaxed mb-4">هنوز مرحله‌ای تعریف نشده است</p>
                <button type="button" onclick="addState()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 text-sm leading-normal">
                  <i class="fa-solid fa-plus ml-2"></i>
                  افزودن اولین مرحله
                </button>
              </div>
              
            </div>
            
          </div>
          
          <!-- Sidebar - Sticky -->
          <div class="lg:sticky lg:top-6 space-y-6 self-start">
            
            <!-- اطلاعات فرایند و پیش‌نمایش -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-text-primary leading-snug">پیش‌نمایش فرایند</h3>
                <div class="flex items-center gap-1.5">
                  <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-mono">#<?= $workflow['id'] ?></span>
                  <span id="statesCountBadge" class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded"><?= count($workflow['states']) ?> مرحله</span>
                </div>
              </div>
              
              <!-- پیش‌نمایش مراحل -->
              <div id="statesPreview">
                <h4 class="text-xs font-medium text-text-muted mb-3 leading-normal">مراحل فرایند:</h4>
                <div id="previewContainer" class="space-y-2">
                  <!-- پیش‌نمایش به صورت داینامیک رندر می‌شود -->
                </div>
                <div id="emptyPreview" class="text-center py-6 text-text-muted hidden">
                  <i class="fa-solid fa-layer-group text-2xl mb-2 opacity-30"></i>
                  <p class="text-xs">مرحله‌ای تعریف نشده</p>
                </div>
              </div>
            </div>
            
            <!-- دکمه‌های عملیاتی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="space-y-4">
                <!-- وضعیت فرایند -->
                <div class="flex items-center justify-between pb-4 border-b border-border-light">
                  <span class="text-sm text-text-secondary leading-normal">وضعیت فرایند</span>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= $workflow['is_active'] ? 'checked' : '' ?> class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:-translate-x-full peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                  </label>
                </div>
                
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white px-5 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-check"></i>
                  <span>ذخیره تغییرات</span>
                </button>
                <a href="/dashboard/workflows/list.php"
                   class="w-full bg-bg-secondary text-text-secondary border border-border-medium px-5 py-3 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-times"></i>
                  <span>انصراف</span>
                </a>
              </div>
            </div>
            
          </div>
          
        </div>
        
      </form>
      
      </div>
    </main>
  </div>
  
  <!-- State Template (hidden) -->
  <template id="stateTemplate">
    <div class="state-item bg-bg-secondary border border-border-light rounded-xl p-4 relative" data-state-id="">
      <div class="absolute right-3 top-3 cursor-move state-drag-handle text-text-muted hover:text-text-primary">
        <i class="fa-solid fa-grip-vertical"></i>
      </div>
      <button type="button" class="absolute left-3 top-3 text-text-muted hover:text-red-600 transition-colors delete-state-btn">
        <i class="fa-solid fa-times"></i>
      </button>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              نام مرحله <span class="text-red-500 mr-1">*</span>
            </label>
            <input type="text" name="states[][name]" required
                   class="state-name-input flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white leading-normal"
                   placeholder="نام مرحله">
          </div>
        </div>
        
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              شناسه
            </label>
            <input type="text" name="states[][slug]"
                   class="state-slug-input flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white leading-normal font-mono"
                   placeholder="state-slug"
                   pattern="[a-z0-9\-]+"
                   dir="ltr">
          </div>
        </div>
        
        <!-- رنگ مرحله - تمام عرض -->
        <div class="md:col-span-2 border border-border-medium rounded-xl overflow-hidden">
          <div class="flex items-center">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal flex-shrink-0">
              رنگ
            </label>
            <div class="flex-1 flex items-center gap-1.5 px-3 py-2 bg-white overflow-x-auto">
              <input type="hidden" name="states[][color]" value="#E3F2FD" class="state-color-input">
              <?php foreach ($stateColors as $clr): ?>
              <button type="button" 
                      class="color-square w-6 h-6 rounded flex-shrink-0 border-2 transition-all duration-150 hover:scale-110 <?= $clr === '#E3F2FD' ? 'border-indigo-600 ring-2 ring-indigo-200' : 'border-transparent hover:border-gray-300' ?>"
                      style="background-color: <?= $clr ?>"
                      data-color="<?= $clr ?>"
                      title="<?= $clr ?>"></button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- موقعیت مرحله -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              موقعیت
            </label>
            <select name="states[][position]" class="state-position-select flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white cursor-pointer leading-normal">
              <option value="start">نقطه شروع</option>
              <option value="middle" selected>میانی</option>
              <option value="final-success">پایان موفق</option>
              <option value="final-failed">پایان ناموفق</option>
              <option value="final-closed">بسته شده</option>
            </select>
          </div>
        </div>
        
        <!-- مسئول پیش‌فرض -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              مسئول پیش‌فرض
            </label>
            <select name="states[][default_assignee_id]" class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white cursor-pointer leading-normal">
              <option value="">بدون مسئول</option>
              <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        
        <div class="md:col-span-2 border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-3 py-3 text-sm text-text-secondary leading-normal">
              توضیحات
            </label>
            <textarea rows="2" name="states[][description]"
                      class="flex-1 px-3 py-3 text-base text-text-primary outline-none resize-none bg-white leading-relaxed"
                      placeholder="توضیحات این مرحله (اختیاری)"></textarea>
          </div>
        </div>
        
      </div>
      
      <input type="hidden" name="states[][id]" value="">
      <input type="hidden" name="states[][order]" class="state-order-input" value="1">
    </div>
  </template>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  let stateCounter = <?= count($workflow['states']) ?>;
  let activeStateElement = null;
  
  const statesContainer = document.getElementById('statesContainer');
  const emptyMessage = document.getElementById('emptyStatesMessage');
  const previewContainer = document.getElementById('previewContainer');
  const stateTemplate = document.getElementById('stateTemplate');
  const stateColors = <?= json_encode($stateColors) ?>;
  
  // Initialize existing states
  initializeExistingStates();
  updatePreview();
  
  function initializeExistingStates() {
    document.querySelectorAll('.state-item').forEach(item => {
      // Color squares handler
      initColorSquares(item);
      
      // Name input handler
      const nameInput = item.querySelector('.state-name-input');
      if (nameInput) {
        nameInput.addEventListener('input', updatePreview);
      }
      
      // Position handler
      const positionSelect = item.querySelector('.state-position-select');
      if (positionSelect) {
        positionSelect.addEventListener('change', updatePreview);
      }
      
      // Delete handler
      const deleteBtn = item.querySelector('.delete-state-btn');
      if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
          if (confirm('آیا از حذف این مرحله اطمینان دارید؟')) {
            item.remove();
            updateStatesUI();
            updatePreview();
          }
        });
      }
      
      // Click handler for active state
      item.addEventListener('click', function(e) {
        if (e.target.closest('.delete-state-btn') || e.target.closest('.state-drag-handle')) return;
        setActiveState(this);
      });
    });
  }
  
  function initColorSquares(stateItem) {
    const colorInput = stateItem.querySelector('.state-color-input');
    const colorSquares = stateItem.querySelectorAll('.color-square');
    
    colorSquares.forEach(square => {
      square.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const color = this.dataset.color;
        colorInput.value = color;
        
        // Update UI
        colorSquares.forEach(s => {
          s.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-200');
          s.classList.add('border-transparent');
        });
        this.classList.remove('border-transparent');
        this.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-200');
        
        updatePreview();
      });
    });
  }
  
  // Add State Button
  document.getElementById('addStateBtn').addEventListener('click', addState);
  
  function addState() {
    stateCounter++;
    
    const clone = stateTemplate.content.cloneNode(true);
    const stateItem = clone.querySelector('.state-item');
    stateItem.dataset.stateId = 'new-' + stateCounter;
    
    // Set order
    const orderInput = stateItem.querySelector('.state-order-input');
    orderInput.value = statesContainer.children.length + 1;
    
    // Set default color
    const colorInput = stateItem.querySelector('.state-color-input');
    const defaultColor = stateColors[(stateCounter - 1) % stateColors.length];
    colorInput.value = defaultColor;
    
    // Update color square selection
    const colorSquares = stateItem.querySelectorAll('.color-square');
    colorSquares.forEach(s => {
      s.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-200');
      s.classList.add('border-transparent');
      if (s.dataset.color === defaultColor) {
        s.classList.remove('border-transparent');
        s.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-200');
      }
    });
    
    // Initialize color squares
    initColorSquares(stateItem);
    
    const nameInput = stateItem.querySelector('.state-name-input');
    nameInput.addEventListener('input', updatePreview);
    
    const positionSelect = stateItem.querySelector('.state-position-select');
    positionSelect.addEventListener('change', updatePreview);
    
    const deleteBtn = stateItem.querySelector('.delete-state-btn');
    deleteBtn.addEventListener('click', function() {
      stateItem.remove();
      updateStatesUI();
      updatePreview();
    });
    
    stateItem.addEventListener('click', function(e) {
      if (e.target.closest('.delete-state-btn') || e.target.closest('.state-drag-handle')) return;
      setActiveState(this);
    });
    
    statesContainer.appendChild(clone);
    updateStatesUI();
    setActiveState(stateItem);
    
    setTimeout(() => nameInput.focus(), 100);
  }
  
  function setActiveState(element) {
    document.querySelectorAll('.state-item').forEach(item => {
      item.classList.remove('ring-2', 'ring-indigo-500');
    });
    if (element) {
      element.classList.add('ring-2', 'ring-indigo-500');
      activeStateElement = element;
    }
  }
  
  function updateStatesUI() {
    const hasStates = statesContainer.children.length > 0;
    emptyMessage.classList.toggle('hidden', hasStates);
    
    // Update states count badge
    const statesCountBadge = document.getElementById('statesCountBadge');
    if (statesCountBadge) {
      statesCountBadge.textContent = statesContainer.children.length + ' مرحله';
    }
    
    // Update order values and input names
    Array.from(statesContainer.children).forEach((item, index) => {
      const orderInput = item.querySelector('.state-order-input');
      orderInput.value = index + 1;
      
      // Update all input names with correct index
      item.querySelectorAll('[name^="states["]').forEach(input => {
        const name = input.getAttribute('name');
        const newName = name.replace(/states\[\d*\]/, `states[${index}]`);
        input.setAttribute('name', newName);
      });
    });
    
    updatePreview();
  }
  
  function updatePreview() {
    const emptyPreview = document.getElementById('emptyPreview');
    previewContainer.innerHTML = '';
    
    const states = Array.from(statesContainer.querySelectorAll('.state-item'));
    
    if (states.length === 0) {
      if (emptyPreview) emptyPreview.classList.remove('hidden');
      return;
    }
    
    if (emptyPreview) emptyPreview.classList.add('hidden');
    
    states.forEach((state, index) => {
      const name = state.querySelector('.state-name-input').value || `مرحله ${index + 1}`;
      const color = state.querySelector('.state-color-input').value;
      const position = state.querySelector('.state-position-select').value;
      
      const isFinal = position.startsWith('final');
      const isStart = position === 'start';
      
      // Position badge (inline)
      let positionBadge = '';
      if (isStart) {
        positionBadge = '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-medium bg-white/60 text-green-700"><i class="fa-solid fa-play text-[7px]"></i>شروع</span>';
      } else if (isFinal) {
        positionBadge = position === 'final-success' 
          ? '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-medium bg-white/60 text-green-700"><i class="fa-solid fa-check text-[7px]"></i>موفق</span>'
          : '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-medium bg-white/60 text-red-600"><i class="fa-solid fa-times text-[7px]"></i>ناموفق</span>';
      }
      
      const stateRow = document.createElement('div');
      stateRow.className = 'relative rounded-lg overflow-hidden mb-1';
      stateRow.innerHTML = `
        <div class="relative px-2.5 py-2 flex items-center gap-2" style="background-color: ${color}">
          <span class="w-6 h-6 flex items-center justify-center text-sm font-bold bg-white/70 text-gray-700 rounded-md flex-shrink-0 shadow-sm">${index + 1}</span>
          <div class="flex-1 text-sm font-medium text-gray-800 truncate">${name}</div>
          ${positionBadge}
        </div>
      `;
      
      previewContainer.appendChild(stateRow);
    });
  }
  
  // Form validation
  document.getElementById('workflowForm').addEventListener('submit', function(e) {
    const states = statesContainer.querySelectorAll('.state-item');
    
    if (states.length === 0) {
      e.preventDefault();
      alert('لطفاً حداقل یک مرحله برای فرایند تعریف کنید');
      return;
    }
    
    let hasStart = false;
    let hasFinal = false;
    
    states.forEach(state => {
      const position = state.querySelector('.state-position-select').value;
      if (position === 'start') hasStart = true;
      if (position.startsWith('final')) hasFinal = true;
    });
    
    if (!hasStart) {
      e.preventDefault();
      alert('لطفاً یک مرحله به عنوان "نقطه شروع" تعریف کنید');
      return;
    }
    
    if (!hasFinal) {
      e.preventDefault();
      alert('لطفاً حداقل یک مرحله "پایانی" تعریف کنید');
      return;
    }
  });
  
  // Drag & Drop
  let draggedItem = null;
  
  statesContainer.addEventListener('dragstart', function(e) {
    if (e.target.classList.contains('state-item')) {
      draggedItem = e.target;
      e.target.style.opacity = '0.5';
    }
  });
  
  statesContainer.addEventListener('dragend', function(e) {
    if (e.target.classList.contains('state-item')) {
      e.target.style.opacity = '1';
      draggedItem = null;
      updateStatesUI();
    }
  });
  
  statesContainer.addEventListener('dragover', function(e) {
    e.preventDefault();
  });
  
  statesContainer.addEventListener('drop', function(e) {
    e.preventDefault();
    const target = e.target.closest('.state-item');
    if (target && draggedItem && target !== draggedItem) {
      const allItems = [...statesContainer.querySelectorAll('.state-item')];
      const draggedIndex = allItems.indexOf(draggedItem);
      const targetIndex = allItems.indexOf(target);
      
      if (draggedIndex < targetIndex) {
        target.after(draggedItem);
      } else {
        target.before(draggedItem);
      }
    }
  });
  
  // Make items draggable
  statesContainer.querySelectorAll('.state-item').forEach(item => {
    item.draggable = true;
  });
  
  const observer = new MutationObserver(function() {
    statesContainer.querySelectorAll('.state-item').forEach(item => {
      item.draggable = true;
    });
    // Auto-set first state position to 'start'
    autoSetFirstStatePosition();
  });
  observer.observe(statesContainer, { childList: true });
  
  // Auto-set first state position to 'start'
  function autoSetFirstStatePosition() {
    const states = statesContainer.querySelectorAll('.state-item');
    if (states.length > 0) {
      const firstStatePosition = states[0].querySelector('.state-position-select');
      if (firstStatePosition && firstStatePosition.value !== 'start') {
        firstStatePosition.value = 'start';
        updatePreview();
      }
    }
  }
  
  // Role badges click handler
  document.querySelectorAll('.role-badge').forEach(badge => {
    badge.addEventListener('click', function(e) {
      const checkbox = this.querySelector('.role-checkbox');
      const icon = this.querySelector('.role-icon');
      
      // Toggle checkbox
      checkbox.checked = !checkbox.checked;
      
      // Update styles
      if (checkbox.checked) {
        this.classList.remove('bg-gray-50', 'border-gray-200', 'text-text-secondary');
        this.classList.add('bg-indigo-50', 'border-indigo-300', 'text-indigo-700');
        icon.classList.remove('fa-circle', 'text-gray-300');
        icon.classList.add('fa-check-circle', 'text-indigo-500');
      } else {
        this.classList.remove('bg-indigo-50', 'border-indigo-300', 'text-indigo-700');
        this.classList.add('bg-gray-50', 'border-gray-200', 'text-text-secondary');
        icon.classList.remove('fa-check-circle', 'text-indigo-500');
        icon.classList.add('fa-circle', 'text-gray-300');
      }
    });
  });
  
  // Initialize: auto-set first state position
  autoSetFirstStatePosition();
  </script>
  
</body>
</html>
