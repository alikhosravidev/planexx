<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'ایجاد فرایند جدید';
$currentPage = 'workflow';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/workflows/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'لیست فرایندها', 'url' => '/dashboard/workflows/list.php'],
    ['label' => 'ایجاد فرایند جدید'],
];

// دپارتمان‌ها (در پروژه واقعی از دیتابیس)
$departments = [
    ['id' => 1, 'name' => 'فروش'],
    ['id' => 2, 'name' => 'مالی'],
    ['id' => 3, 'name' => 'منابع انسانی'],
    ['id' => 4, 'name' => 'فنی'],
    ['id' => 5, 'name' => 'تولید'],
    ['id' => 6, 'name' => 'عمومی'],
];

// کاربران (در پروژه واقعی از دیتابیس)
$users = [
    ['id' => 1, 'name' => 'علی احمدی', 'department' => 'فروش'],
    ['id' => 2, 'name' => 'مریم رضایی', 'department' => 'مالی'],
    ['id' => 3, 'name' => 'سارا محمدی', 'department' => 'منابع انسانی'],
    ['id' => 4, 'name' => 'رضا کریمی', 'department' => 'فنی'],
    ['id' => 5, 'name' => 'فاطمه نوری', 'department' => 'تولید'],
];

// نقش‌ها (در پروژه واقعی از دیتابیس)
$roles = [
    ['id' => 1, 'name' => 'مدیر سیستم'],
    ['id' => 2, 'name' => 'مدیر فروش'],
    ['id' => 3, 'name' => 'کارشناس فروش'],
    ['id' => 4, 'name' => 'مدیر مالی'],
    ['id' => 5, 'name' => 'حسابدار'],
    ['id' => 6, 'name' => 'کارشناس HR'],
];

// رنگ‌های پیشنهادی برای مراحل
$stateColors = [
    '#E3F2FD', // آبی روشن
    '#E8F5E9', // سبز روشن
    '#FFF3E0', // نارنجی روشن
    '#F3E5F5', // بنفش روشن
    '#E0F2F1', // سبزآبی روشن
    '#FFF8E1', // زرد روشن
    '#FFEBEE', // قرمز روشن
    '#E8EAF6', // نیلی روشن
    '#FCE4EC', // صورتی روشن
    '#ECEFF1', // خاکستری روشن
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST" id="workflowForm">
        
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
                  <p class="text-sm text-text-secondary leading-normal mt-1">مشخصات اصلی فرایند را وارد کنید</p>
                </div>
              </div>
              
              <div class="space-y-4">
                
                <!-- نام فرایند -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      نام فرایند <span class="text-red-500 mr-1">*</span>
                    </label>
                    <input type="text" name="name" required
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="مثال: فرایند فروش، استخدام نیرو">
                  </div>
                </div>
                
                <!-- شناسه یکتا (Slug) -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      شناسه یکتا
                    </label>
                    <input type="text" name="slug" id="slugInput"
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal font-mono"
                           placeholder="sales-process"
                           pattern="[a-z0-9\-]+"
                           dir="ltr">
                  </div>
                </div>
                <p class="text-xs text-text-muted leading-normal -mt-2 mr-4">فقط حروف انگلیسی کوچک، اعداد و خط تیره مجاز است. در صورت خالی بودن، به صورت خودکار از نام فرایند ساخته می‌شود.</p>
                
                <!-- توضیحات -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
                      توضیحات
                    </label>
                    <textarea rows="3" name="description"
                              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                              placeholder="توضیحات مختصر درباره این فرایند"></textarea>
                  </div>
                </div>
                
                <!-- دپارتمان -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      دپارتمان
                    </label>
                    <select name="department_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">انتخاب کنید (اختیاری)</option>
                      <?php foreach ($departments as $dept): ?>
                      <option value="<?= $dept['id'] ?>"><?= $dept['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                
                <!-- مالک فرایند -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      مالک فرایند
                    </label>
                    <select name="workflow_owner_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">انتخاب کنید (اختیاری)</option>
                      <?php foreach ($users as $user): ?>
                      <option value="<?= $user['id'] ?>"><?= $user['name'] ?> - <?= $user['department'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                
                <!-- نقش‌های مجاز -->
                <div class="border border-border-medium rounded-xl p-4">
                  <label class="block text-sm text-text-secondary mb-3 leading-normal">نقش‌های مجاز برای دسترسی</label>
                  <div class="flex flex-wrap gap-3">
                    <?php foreach ($roles as $role): ?>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="checkbox" name="allowed_roles[]" value="<?= $role['id'] ?>" class="w-4 h-4 text-indigo-600 accent-indigo-600">
                      <span class="text-sm text-text-primary leading-normal"><?= $role['name'] ?></span>
                    </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                
                <!-- وضعیت فرایند -->
                <div class="border border-border-medium rounded-xl p-4">
                  <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت فرایند</label>
                  <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 accent-indigo-600">
                      <span class="text-base text-text-primary leading-normal">فعال</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="0" class="w-4 h-4 text-indigo-600 accent-indigo-600">
                      <span class="text-base text-text-primary leading-normal">غیرفعال</span>
                    </label>
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
                    <p class="text-sm text-text-secondary leading-normal mt-1">مراحل مختلف این فرایند را تعریف کنید</p>
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
                <!-- مراحل به صورت داینامیک اضافه می‌شوند -->
              </div>
              
              <!-- Empty State -->
              <div id="emptyStatesMessage" class="text-center py-12 border-2 border-dashed border-border-medium rounded-xl">
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
              
              <!-- States Preview -->
              <div id="statesPreview" class="mt-6 pt-6 border-t border-border-light hidden">
                <h3 class="text-sm font-semibold text-text-secondary mb-4 leading-normal">پیش‌نمایش مراحل:</h3>
                <div id="previewContainer" class="flex items-stretch gap-0 overflow-x-auto pb-2">
                  <!-- پیش‌نمایش به صورت داینامیک رندر می‌شود -->
                </div>
              </div>
              
            </div>
            
          </div>
          
          <!-- Sidebar -->
          <div class="space-y-6">
            
            <!-- راهنما -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
              <div class="flex items-start gap-3 mb-4">
                <i class="fa-solid fa-circle-info text-blue-600 text-xl"></i>
                <h3 class="text-base font-semibold text-blue-900 leading-snug">راهنمای ایجاد فرایند</h3>
              </div>
              <ul class="space-y-3 text-sm text-blue-800 leading-relaxed">
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>نام فرایند باید یکتا و گویا باشد</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>حداقل یک مرحله تعریف کنید</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>اولین مرحله باید نقطه شروع باشد</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>حداقل یک مرحله پایانی تعریف کنید</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>ترتیب مراحل با Drag & Drop قابل تغییر است</span>
                </li>
              </ul>
            </div>
            
            <!-- رنگ‌های پیشنهادی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <h3 class="text-base font-semibold text-text-primary leading-snug mb-4">رنگ‌های پیشنهادی</h3>
              <div class="grid grid-cols-5 gap-2">
                <?php foreach ($stateColors as $color): ?>
                <button type="button" 
                        class="w-10 h-10 rounded-lg border-2 border-transparent hover:border-indigo-400 transition-all duration-200 color-picker-btn"
                        style="background-color: <?= $color ?>"
                        data-color="<?= $color ?>"></button>
                <?php endforeach; ?>
              </div>
              <p class="text-xs text-text-muted leading-normal mt-3">روی هر رنگ کلیک کنید تا در مرحله فعال اعمال شود</p>
            </div>
            
            <!-- دکمه‌های عملیاتی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="space-y-3">
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white px-5 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-check"></i>
                  <span>ایجاد فرایند</span>
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
            <input type="text" name="states[][name]" required
                   class="state-name-input flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white leading-normal"
                   placeholder="نام مرحله">
          </div>
        </div>
        
        <!-- شناسه مرحله -->
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
        
        <!-- رنگ مرحله -->
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              رنگ
            </label>
            <div class="flex-1 flex items-center gap-2 px-3 bg-white">
              <input type="color" name="states[][color]" value="#E3F2FD"
                     class="state-color-input w-8 h-8 rounded cursor-pointer border-0">
              <input type="text" 
                     class="state-color-text flex-1 py-3 text-sm text-text-muted outline-none bg-transparent font-mono"
                     value="#E3F2FD"
                     readonly>
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
        <div class="md:col-span-2 border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
              مسئول پیش‌فرض
            </label>
            <select name="states[][default_assignee_id]" class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-white cursor-pointer leading-normal">
              <option value="">بدون مسئول پیش‌فرض</option>
              <?php foreach ($users as $user): ?>
              <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
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
            <textarea rows="2" name="states[][description]"
                      class="flex-1 px-3 py-3 text-base text-text-primary outline-none resize-none bg-white leading-relaxed"
                      placeholder="توضیحات این مرحله (اختیاری)"></textarea>
          </div>
        </div>
        
      </div>
      
      <!-- Hidden Order Field -->
      <input type="hidden" name="states[][order]" class="state-order-input" value="1">
    </div>
  </template>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  let stateCounter = 0;
  let activeStateElement = null;
  
  const statesContainer = document.getElementById('statesContainer');
  const emptyMessage = document.getElementById('emptyStatesMessage');
  const statesPreview = document.getElementById('statesPreview');
  const previewContainer = document.getElementById('previewContainer');
  const stateTemplate = document.getElementById('stateTemplate');
  
  // Add State Button
  document.getElementById('addStateBtn').addEventListener('click', addState);
  
  function addState() {
    stateCounter++;
    
    const clone = stateTemplate.content.cloneNode(true);
    const stateItem = clone.querySelector('.state-item');
    stateItem.dataset.stateId = stateCounter;
    
    // Set order
    const orderInput = stateItem.querySelector('.state-order-input');
    orderInput.value = statesContainer.children.length + 1;
    
    // Set default color based on counter
    const colors = <?= json_encode($stateColors) ?>;
    const colorInput = stateItem.querySelector('.state-color-input');
    const colorText = stateItem.querySelector('.state-color-text');
    const defaultColor = colors[(stateCounter - 1) % colors.length];
    colorInput.value = defaultColor;
    colorText.value = defaultColor;
    
    // Color input change handler
    colorInput.addEventListener('input', function() {
      colorText.value = this.value.toUpperCase();
      updatePreview();
    });
    
    // Name input change handler for preview
    const nameInput = stateItem.querySelector('.state-name-input');
    nameInput.addEventListener('input', updatePreview);
    
    // Position change handler for preview
    const positionSelect = stateItem.querySelector('.state-position-select');
    positionSelect.addEventListener('change', updatePreview);
    
    // Delete button handler
    const deleteBtn = stateItem.querySelector('.delete-state-btn');
    deleteBtn.addEventListener('click', function() {
      stateItem.remove();
      updateStatesUI();
      updatePreview();
    });
    
    // Click handler for active state
    stateItem.addEventListener('click', function(e) {
      if (e.target.closest('.delete-state-btn') || e.target.closest('.state-drag-handle')) return;
      setActiveState(this);
    });
    
    statesContainer.appendChild(clone);
    updateStatesUI();
    setActiveState(stateItem);
    
    // Focus on name input
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
    statesPreview.classList.toggle('hidden', !hasStates);
    
    // Update order values
    Array.from(statesContainer.children).forEach((item, index) => {
      const orderInput = item.querySelector('.state-order-input');
      orderInput.value = index + 1;
    });
    
    updatePreview();
  }
  
  function updatePreview() {
    previewContainer.innerHTML = '';
    
    const states = Array.from(statesContainer.querySelectorAll('.state-item'));
    
    states.forEach((state, index) => {
      const name = state.querySelector('.state-name-input').value || `مرحله ${index + 1}`;
      const color = state.querySelector('.state-color-input').value;
      const position = state.querySelector('.state-position-select').value;
      
      const isLast = index === states.length - 1;
      const isFinal = position.startsWith('final');
      const isStart = position === 'start';
      
      const stateBox = document.createElement('div');
      stateBox.className = 'flex items-stretch flex-shrink-0';
      stateBox.innerHTML = `
        <div class="relative min-w-[120px] px-4 py-3 text-center rounded-lg border border-border-light" 
             style="background-color: ${color}">
          ${isStart ? '<div class="absolute -top-2 right-2"><i class="fa-solid fa-play text-green-600 text-xs"></i></div>' : ''}
          ${isFinal ? '<div class="absolute -top-2 left-2"><i class="fa-solid fa-flag-checkered text-gray-600 text-xs"></i></div>' : ''}
          <div class="text-sm font-medium text-text-primary leading-snug">${name}</div>
        </div>
        ${!isLast ? '<div class="flex items-center px-1 text-gray-300"><i class="fa-solid fa-chevron-left text-lg"></i></div>' : ''}
      `;
      
      previewContainer.appendChild(stateBox);
    });
  }
  
  // Color picker buttons
  document.querySelectorAll('.color-picker-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      if (activeStateElement) {
        const color = this.dataset.color;
        const colorInput = activeStateElement.querySelector('.state-color-input');
        const colorText = activeStateElement.querySelector('.state-color-text');
        colorInput.value = color;
        colorText.value = color;
        updatePreview();
      }
    });
  });
  
  // Auto-generate slug from name
  document.querySelector('input[name="name"]').addEventListener('input', function() {
    const slugInput = document.getElementById('slugInput');
    if (!slugInput.dataset.manual) {
      slugInput.value = generateSlug(this.value);
    }
  });
  
  document.getElementById('slugInput').addEventListener('input', function() {
    this.dataset.manual = true;
  });
  
  function generateSlug(text) {
    // Simple Persian to English slug conversion
    const persianMap = {
      'آ': 'a', 'ا': 'a', 'ب': 'b', 'پ': 'p', 'ت': 't', 'ث': 's',
      'ج': 'j', 'چ': 'ch', 'ح': 'h', 'خ': 'kh', 'د': 'd', 'ذ': 'z',
      'ر': 'r', 'ز': 'z', 'ژ': 'zh', 'س': 's', 'ش': 'sh', 'ص': 's',
      'ض': 'z', 'ط': 't', 'ظ': 'z', 'ع': 'a', 'غ': 'gh', 'ف': 'f',
      'ق': 'gh', 'ک': 'k', 'گ': 'g', 'ل': 'l', 'م': 'm', 'ن': 'n',
      'و': 'v', 'ه': 'h', 'ی': 'y', 'ئ': 'y', ' ': '-'
    };
    
    let slug = '';
    for (let char of text) {
      slug += persianMap[char] || char.toLowerCase();
    }
    
    return slug.replace(/[^a-z0-9\-]/g, '').replace(/-+/g, '-').replace(/^-|-$/g, '');
  }
  
  // Form validation
  document.getElementById('workflowForm').addEventListener('submit', function(e) {
    const states = statesContainer.querySelectorAll('.state-item');
    
    if (states.length === 0) {
      e.preventDefault();
      alert('لطفاً حداقل یک مرحله برای فرایند تعریف کنید');
      return;
    }
    
    // Check for start state
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
  
  // Drag & Drop for reordering (simple implementation)
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
  
  // Make state items draggable
  const observer = new MutationObserver(function() {
    statesContainer.querySelectorAll('.state-item').forEach(item => {
      item.draggable = true;
    });
  });
  observer.observe(statesContainer, { childList: true });
  </script>
  
</body>
</html>
