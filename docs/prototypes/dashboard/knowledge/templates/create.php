<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'افزودن قالب تجربه جدید';
$currentPage = 'knowledge-base';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/knowledge/templates/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'پایگاه تجربه سازمانی', 'url' => '/dashboard/knowledge/index.php'],
    ['label' => 'قالب‌های تجربه', 'url' => '/dashboard/knowledge/templates/list.php'],
    ['label' => 'افزودن قالب جدید'],
];

// فیلدهای مشترک همه تجربیات (غیرقابل تغییر)
$commonFields = [
    ['name' => 'title', 'label' => 'عنوان تجربه', 'type' => 'text', 'required' => true],
    ['name' => 'summary', 'label' => 'خلاصه تجربه', 'type' => 'textarea', 'required' => true],
    ['name' => 'description', 'label' => 'شرح کامل تجربه', 'type' => 'textarea', 'required' => true],
    ['name' => 'category', 'label' => 'دسته‌بندی', 'type' => 'select', 'required' => true],
    ['name' => 'tags', 'label' => 'برچسب‌ها', 'type' => 'text', 'required' => false],
    ['name' => 'author', 'label' => 'نویسنده', 'type' => 'text', 'required' => true],
    ['name' => 'date', 'label' => 'تاریخ', 'type' => 'date', 'required' => true],
];

// انواع فیلدهای قابل افزودن
$fieldTypes = [
    'text'     => 'متن کوتاه',
    'textarea' => 'متن بلند',
    'number'   => 'عدد',
    'date'     => 'تاریخ',
    'select'   => 'انتخاب از لیست',
    'radio'    => 'گزینه‌های رادیویی',
    'checkbox' => 'چک‌باکس',
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
      
      <form method="POST">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- فرم اصلی -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- اطلاعات پایه قالب -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه قالب</h2>
              
              <div class="space-y-4">
                
                <!-- نام قالب -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      نام قالب <span class="text-red-500 mr-1">*</span>
                    </label>
                    <input type="text" name="name" required
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="مثال: تجربه قراردادی">
                  </div>
                </div>
                
                <!-- دپارتمان -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      دپارتمان <span class="text-red-500 mr-1">*</span>
                    </label>
                    <select name="department_id" required class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">انتخاب کنید</option>
                      <option value="1">مدیریت</option>
                      <option value="2">فروش</option>
                      <option value="3">فنی</option>
                      <option value="4">منابع انسانی</option>
                      <option value="5">مالی</option>
                      <option value="6">تولید</option>
                    </select>
                  </div>
                </div>
                
                <!-- توضیحات قالب -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
                      توضیحات قالب
                    </label>
                    <textarea rows="3" name="description"
                              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                              placeholder="توضیحات و راهنمای استفاده از این قالب"></textarea>
                  </div>
                </div>
                
                <!-- وضعیت -->
                <div class="flex items-center gap-6 px-lg py-3.5">
                  <label class="text-sm text-text-secondary leading-normal min-w-[140px]">
                    وضعیت
                  </label>
                  <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="1" checked class="w-4 h-4 text-teal-600 accent-teal-600">
                      <span class="text-base text-text-primary leading-normal">فعال</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio" name="is_active" value="0" class="w-4 h-4 text-teal-600 accent-teal-600">
                      <span class="text-base text-text-primary leading-normal">غیرفعال</span>
                    </label>
                  </div>
                </div>
                
              </div>
            </div>
            
            <!-- فیلدهای مشترک (غیرقابل تغییر) -->
            <div class="bg-gradient-to-br from-slate-50 to-gray-50 border-2 border-slate-200 rounded-2xl p-6">
              <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 bg-slate-600 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fa-solid fa-lock text-white"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-text-primary leading-snug">فیلدهای مشترک (غیرقابل تغییر)</h2>
                  <p class="text-sm text-text-secondary leading-normal mt-1">این فیلدها برای همه تجربیات ثابت و الزامی هستند</p>
                </div>
              </div>
              
              <div class="space-y-3">
                <?php foreach ($commonFields as $field): ?>
                <div class="bg-white border border-slate-200 rounded-xl p-4 opacity-75">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <i class="fa-solid fa-grip-vertical text-slate-300"></i>
                      <div>
                        <p class="text-sm font-medium text-text-primary leading-normal">
                          <?= $field['label'] ?>
                          <?php if ($field['required']): ?>
                            <span class="text-red-500 mr-1">*</span>
                          <?php endif; ?>
                        </p>
                        <p class="text-xs text-text-muted leading-normal">نوع: <?= $field['type'] ?></p>
                      </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                      <i class="fa-solid fa-lock text-[10px]"></i>
                      ثابت
                    </span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            
            <!-- Form Builder - فیلدهای سفارشی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="flex items-center justify-between mb-6">
                <div>
                  <h2 class="text-lg font-semibold text-text-primary leading-snug">فیلدهای سفارشی</h2>
                  <p class="text-sm text-text-secondary leading-normal mt-1">فیلدهای اختصاصی این قالب را تعریف کنید</p>
                </div>
                <button type="button" id="addFieldBtn"
                        class="bg-teal-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition-all duration-200 flex items-center gap-2 text-sm leading-normal">
                  <i class="fa-solid fa-plus"></i>
                  <span>افزودن فیلد</span>
                </button>
              </div>
              
              <!-- Custom Fields Container -->
              <div id="customFieldsContainer" class="space-y-4">
                <!-- فیلدها با JavaScript اضافه می‌شوند -->
                <div class="text-center py-12 text-text-muted">
                  <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-20"></i>
                  <p class="text-base leading-normal">هنوز فیلدی اضافه نشده است</p>
                  <p class="text-sm leading-normal mt-1">روی دکمه "افزودن فیلد" کلیک کنید</p>
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
                <h3 class="text-base font-semibold text-blue-900 leading-snug">راهنمای ساخت قالب</h3>
              </div>
              <ul class="space-y-3 text-sm text-blue-800 leading-relaxed">
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>ابتدا دپارتمان مربوطه را انتخاب کنید</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>فیلدهای مشترک برای همه قالب‌ها ثابت است</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>فیلدهای سفارشی مخصوص این قالب هستند</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>می‌توانید ترتیب فیلدها را تغییر دهید</span>
                </li>
              </ul>
            </div>
            
            <!-- دکمه‌های عملیاتی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
              <div class="space-y-3">
                <button type="submit" 
                        class="w-full bg-teal-600 text-white px-5 py-3 rounded-lg font-medium hover:bg-teal-700 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-check"></i>
                  <span>ذخیره قالب</span>
                </button>
                <a href="/dashboard/knowledge/templates/list.php"
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
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  let fieldCounter = 0;
  
  // افزودن فیلد جدید
  document.getElementById('addFieldBtn').addEventListener('click', function() {
    fieldCounter++;
    
    const container = document.getElementById('customFieldsContainer');
    
    // حذف پیام خالی بودن
    if (container.querySelector('.text-center')) {
      container.innerHTML = '';
    }
    
    const fieldHtml = `
      <div class="bg-slate-50 border border-border-medium rounded-xl p-4" data-field-id="${fieldCounter}">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-grip-vertical text-text-muted cursor-move"></i>
            <span class="text-sm font-medium text-text-primary leading-normal">فیلد شماره ${fieldCounter}</span>
          </div>
          <button type="button" class="removeFieldBtn w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200">
            <i class="fa-solid fa-trash text-sm"></i>
          </button>
        </div>
        
        <div class="space-y-3">
          <!-- نام فیلد -->
          <div class="border border-border-medium rounded-lg overflow-hidden focus-within:border-teal-600 transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-white border-l border-border-light min-w-[100px] px-3 py-2.5 text-xs text-text-secondary flex items-center leading-normal">
                نام فیلد
              </label>
              <input type="text" name="custom_fields[${fieldCounter}][label]" required
                     class="flex-1 px-3 py-2.5 text-sm text-text-primary outline-none bg-white leading-normal"
                     placeholder="مثال: شماره قرارداد">
            </div>
          </div>
          
          <!-- نوع فیلد -->
          <div class="border border-border-medium rounded-lg overflow-hidden focus-within:border-teal-600 transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-white border-l border-border-light min-w-[100px] px-3 py-2.5 text-xs text-text-secondary flex items-center leading-normal">
                نوع فیلد
              </label>
              <select name="custom_fields[${fieldCounter}][type]" required
                      class="flex-1 px-3 py-2.5 text-sm text-text-primary outline-none bg-white cursor-pointer leading-normal field-type-select">
                <option value="text">متن کوتاه</option>
                <option value="textarea">متن بلند</option>
                <option value="number">عدد</option>
                <option value="date">تاریخ</option>
                <option value="select">انتخاب از لیست</option>
                <option value="radio">گزینه‌های رادیویی</option>
                <option value="checkbox">چک‌باکس</option>
              </select>
            </div>
          </div>
          
          <!-- گزینه‌ها (فقط برای select, radio, checkbox) -->
          <div class="options-container hidden">
            <div class="border border-border-medium rounded-lg overflow-hidden">
              <div class="flex">
                <label class="bg-white border-l border-border-light min-w-[100px] px-3 py-2.5 text-xs text-text-secondary leading-normal">
                  گزینه‌ها
                </label>
                <textarea name="custom_fields[${fieldCounter}][options]" rows="3"
                          class="flex-1 px-3 py-2.5 text-sm text-text-primary outline-none resize-none bg-white leading-relaxed"
                          placeholder="هر گزینه در یک خط"></textarea>
              </div>
            </div>
          </div>
          
          <!-- الزامی بودن -->
          <div class="flex items-center gap-2 px-3">
            <input type="checkbox" name="custom_fields[${fieldCounter}][required]" value="1" 
                   class="w-4 h-4 text-teal-600 accent-teal-600">
            <label class="text-sm text-text-primary leading-normal">فیلد الزامی است</label>
          </div>
        </div>
      </div>
    `;
    
    container.insertAdjacentHTML('beforeend', fieldHtml);
    
    // افزودن event listener برای نوع فیلد
    const newField = container.lastElementChild;
    const typeSelect = newField.querySelector('.field-type-select');
    const optionsContainer = newField.querySelector('.options-container');
    
    typeSelect.addEventListener('change', function() {
      if (['select', 'radio', 'checkbox'].includes(this.value)) {
        optionsContainer.classList.remove('hidden');
      } else {
        optionsContainer.classList.add('hidden');
      }
    });
    
    // افزودن event listener برای دکمه حذف
    newField.querySelector('.removeFieldBtn').addEventListener('click', function() {
      if (confirm('آیا از حذف این فیلد اطمینان دارید؟')) {
        newField.remove();
        
        // اگر همه فیلدها حذف شدند، پیام خالی نمایش داده شود
        if (container.children.length === 0) {
          container.innerHTML = `
            <div class="text-center py-12 text-text-muted">
              <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-20"></i>
              <p class="text-base leading-normal">هنوز فیلدی اضافه نشده است</p>
              <p class="text-sm leading-normal mt-1">روی دکمه "افزودن فیلد" کلیک کنید</p>
            </div>
          `;
        }
      }
    });
  });
  </script>
  
</body>
</html>


