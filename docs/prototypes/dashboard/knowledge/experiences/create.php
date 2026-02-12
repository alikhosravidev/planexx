<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'ثبت تجربه جدید';
$currentPage = 'knowledge-base';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'بازگشت به لیست', 'url' => '/dashboard/knowledge/experiences/list.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline']
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'پایگاه تجربه سازمانی', 'url' => '/dashboard/knowledge/index.php'],
    ['label' => 'مدیریت تجربیات', 'url' => '/dashboard/knowledge/experiences/list.php'],
    ['label' => 'ثبت تجربه جدید'],
];

// قالب‌های تجربه به تفکیک دپارتمان (در پروژه واقعی از دیتابیس)
$departmentTemplates = [
    'مالی' => [
        ['id' => 1, 'name' => 'تجربه قراردادی', 'fields' => [
            ['name' => 'contract_number', 'label' => 'شماره قرارداد', 'type' => 'text', 'required' => true],
            ['name' => 'contract_value', 'label' => 'مبلغ قرارداد (ریال)', 'type' => 'number', 'required' => true],
            ['name' => 'contractor', 'label' => 'طرف قرارداد', 'type' => 'text', 'required' => true],
            ['name' => 'contract_type', 'label' => 'نوع قرارداد', 'type' => 'select', 'options' => ['خرید', 'فروش', 'خدماتی', 'مشاوره'], 'required' => true],
            ['name' => 'payment_terms', 'label' => 'شرایط پرداخت', 'type' => 'textarea', 'required' => false],
        ]],
        ['id' => 2, 'name' => 'تجربیات مالی عمومی', 'fields' => [
            ['name' => 'financial_impact', 'label' => 'تاثیر مالی', 'type' => 'textarea', 'required' => true],
            ['name' => 'cost_reduction', 'label' => 'میزان کاهش هزینه (درصد)', 'type' => 'number', 'required' => false],
        ]],
    ],
    'تولید' => [
        ['id' => 3, 'name' => 'بهبود فرآیند تولید', 'fields' => [
            ['name' => 'process_name', 'label' => 'نام فرآیند', 'type' => 'text', 'required' => true],
            ['name' => 'old_duration', 'label' => 'مدت زمان قبلی (ساعت)', 'type' => 'number', 'required' => true],
            ['name' => 'new_duration', 'label' => 'مدت زمان جدید (ساعت)', 'type' => 'number', 'required' => true],
            ['name' => 'efficiency_increase', 'label' => 'افزایش بهره‌وری (درصد)', 'type' => 'number', 'required' => true],
            ['name' => 'implementation_cost', 'label' => 'هزینه پیاده‌سازی (ریال)', 'type' => 'number', 'required' => false],
            ['name' => 'obstacles', 'label' => 'موانع و چالش‌ها', 'type' => 'textarea', 'required' => false],
        ]],
    ],
    'منابع انسانی' => [
        ['id' => 4, 'name' => 'استخدام و جذب نیرو', 'fields' => [
            ['name' => 'position', 'label' => 'عنوان موقعیت شغلی', 'type' => 'text', 'required' => true],
            ['name' => 'hiring_duration', 'label' => 'مدت زمان استخدام (روز)', 'type' => 'number', 'required' => true],
            ['name' => 'applicants_count', 'label' => 'تعداد متقاضیان', 'type' => 'number', 'required' => false],
            ['name' => 'recruitment_channel', 'label' => 'کانال جذب', 'type' => 'select', 'options' => ['سایت‌های کاریابی', 'شبکه‌های اجتماعی', 'دانشگاه‌ها', 'معرفی کارکنان', 'سایر'], 'required' => true],
        ]],
    ],
    'فروش' => [
        ['id' => 5, 'name' => 'استراتژی فروش', 'fields' => [
            ['name' => 'product_name', 'label' => 'نام محصول/خدمت', 'type' => 'text', 'required' => true],
            ['name' => 'sales_increase', 'label' => 'افزایش فروش (درصد)', 'type' => 'number', 'required' => true],
            ['name' => 'marketing_channel', 'label' => 'کانال بازاریابی', 'type' => 'checkbox', 'options' => ['شبکه‌های اجتماعی', 'تبلیغات آنلاین', 'رویدادها', 'ایمیل مارکتینگ', 'تلفنی'], 'required' => false],
            ['name' => 'customer_feedback', 'label' => 'بازخورد مشتریان', 'type' => 'textarea', 'required' => false],
        ]],
    ],
    'فنی' => [
        ['id' => 6, 'name' => 'توسعه نرم‌افزار', 'fields' => [
            ['name' => 'project_name', 'label' => 'نام پروژه', 'type' => 'text', 'required' => true],
            ['name' => 'technology_stack', 'label' => 'تکنولوژی‌های استفاده شده', 'type' => 'text', 'required' => true],
            ['name' => 'development_time', 'label' => 'مدت زمان توسعه (روز)', 'type' => 'number', 'required' => true],
            ['name' => 'team_size', 'label' => 'تعداد اعضای تیم', 'type' => 'number', 'required' => false],
            ['name' => 'challenges', 'label' => 'چالش‌های فنی', 'type' => 'textarea', 'required' => false],
            ['name' => 'code_quality', 'label' => 'کیفیت کد', 'type' => 'radio', 'options' => ['عالی', 'خوب', 'متوسط', 'نیاز به بهبود'], 'required' => false],
        ]],
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
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
      
      <form method="POST">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- فرم اصلی -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- انتخاب دپارتمان و قالب -->
            <div class="bg-teal-50 border-2 border-teal-200 rounded-2xl p-6">
              <div class="flex items-start gap-3 mb-6">
                <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">
                  <i class="fa-solid fa-filter text-white"></i>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-text-primary leading-snug">انتخاب دپارتمان و قالب</h2>
                  <p class="text-sm text-text-secondary leading-normal mt-1">ابتدا دپارتمان و سپس قالب مناسب را انتخاب کنید</p>
                </div>
              </div>
              
              <div class="space-y-4">
                
                <!-- انتخاب دپارتمان -->
                <div class="border border-teal-300 rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200 bg-white">
                  <div class="flex items-stretch">
                    <label class="bg-teal-50 border-l border-teal-200 min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      دپارتمان <span class="text-red-500 mr-1">*</span>
                    </label>
                    <select name="department" id="departmentSelect" required
                            class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">ابتدا دپارتمان را انتخاب کنید</option>
                      <?php foreach (array_keys($departmentTemplates) as $dept): ?>
                      <option value="<?= $dept ?>"><?= $dept ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                
                <!-- انتخاب قالب -->
                <div class="border border-teal-300 rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200 bg-white" id="templateSelectContainer" style="display: none;">
                  <div class="flex items-stretch">
                    <label class="bg-teal-50 border-l border-teal-200 min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      قالب تجربه <span class="text-red-500 mr-1">*</span>
                    </label>
                    <select name="template_id" id="templateSelect" required
                            class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">قالب را انتخاب کنید</option>
                    </select>
                  </div>
                </div>
                
              </div>
            </div>
            
            <!-- فیلدهای مشترک -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6" id="commonFieldsSection" style="display: none;">
              <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه تجربه</h2>
              
              <div class="space-y-4">
                
                <!-- عنوان تجربه -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      عنوان تجربه <span class="text-red-500 mr-1">*</span>
                    </label>
                    <input type="text" name="title" required
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="عنوان خلاصه و گویا برای تجربه">
                  </div>
                </div>
                
                <!-- خلاصه تجربه -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
                      خلاصه تجربه <span class="text-red-500 mr-1">*</span>
                    </label>
                    <textarea rows="3" name="summary" required
                              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                              placeholder="خلاصه‌ای کوتاه از تجربه (حداکثر ۲۰۰ کاراکتر)"></textarea>
                  </div>
                </div>
                
                <!-- شرح کامل تجربه -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
                      شرح کامل <span class="text-red-500 mr-1">*</span>
                    </label>
                    <textarea rows="6" name="description" required
                              class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                              placeholder="شرح کامل تجربه، چالش‌ها، راه‌حل‌ها و نتایج"></textarea>
                  </div>
                </div>
                
                <!-- دسته‌بندی -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      دسته‌بندی <span class="text-red-500 mr-1">*</span>
                    </label>
                    <select name="category" required class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                      <option value="">انتخاب کنید</option>
                      <option value="بهبود فرآیند">بهبود فرآیند</option>
                      <option value="کاهش هزینه">کاهش هزینه</option>
                      <option value="افزایش کارایی">افزایش کارایی</option>
                      <option value="نوآوری">نوآوری</option>
                      <option value="مدیریت بحران">مدیریت بحران</option>
                      <option value="سایر">سایر</option>
                    </select>
                  </div>
                </div>
                
                <!-- برچسب‌ها -->
                <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
                  <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                      برچسب‌ها
                    </label>
                    <input type="text" name="tags"
                           class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                           placeholder="برچسب‌ها را با کاما جدا کنید">
                  </div>
                </div>
                
              </div>
            </div>
            
            <!-- فیلدهای سفارشی (بر اساس قالب) -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6" id="customFieldsSection" style="display: none;">
              <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات تخصصی</h2>
              <div id="customFieldsContainer" class="space-y-4">
                <!-- فیلدها به صورت داینامیک اضافه می‌شوند -->
              </div>
            </div>
            
          </div>
          
          <!-- Sidebar -->
          <div class="space-y-6">
            
            <!-- راهنما -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
              <div class="flex items-start gap-3 mb-4">
                <i class="fa-solid fa-circle-info text-blue-600 text-xl"></i>
                <h3 class="text-base font-semibold text-blue-900 leading-snug">راهنمای ثبت تجربه</h3>
              </div>
              <ul class="space-y-3 text-sm text-blue-800 leading-relaxed">
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>ابتدا دپارتمان خود را انتخاب کنید</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>سپس قالب مناسب را انتخاب کنید</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>فیلدهای الزامی با ستاره مشخص شده‌اند</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-blue-600 mt-0.5"></i>
                  <span>تجربه خود را به صورت واضح و دقیق شرح دهید</span>
                </li>
              </ul>
            </div>
            
            <!-- دکمه‌های عملیاتی -->
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6" id="actionButtonsSection" style="display: none;">
              <div class="space-y-3">
                <button type="submit" 
                        class="w-full bg-teal-600 text-white px-5 py-3 rounded-lg font-medium hover:bg-teal-700 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-check"></i>
                  <span>ثبت تجربه</span>
                </button>
                <button type="button" 
                        class="w-full bg-slate-100 text-text-primary px-5 py-3 rounded-lg font-medium hover:bg-slate-200 transition-all duration-200 flex items-center justify-center gap-2 text-base leading-normal">
                  <i class="fa-solid fa-save"></i>
                  <span>ذخیره پیش‌نویس</span>
                </button>
                <a href="/dashboard/knowledge/experiences/list.php"
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
  const departmentTemplates = <?= json_encode($departmentTemplates) ?>;
  
  const departmentSelect = document.getElementById('departmentSelect');
  const templateSelectContainer = document.getElementById('templateSelectContainer');
  const templateSelect = document.getElementById('templateSelect');
  const commonFieldsSection = document.getElementById('commonFieldsSection');
  const customFieldsSection = document.getElementById('customFieldsSection');
  const customFieldsContainer = document.getElementById('customFieldsContainer');
  const actionButtonsSection = document.getElementById('actionButtonsSection');
  
  // انتخاب دپارتمان
  departmentSelect.addEventListener('change', function() {
    const department = this.value;
    
    if (department && departmentTemplates[department]) {
      // نمایش انتخاب قالب
      templateSelectContainer.style.display = '';
      
      // پر کردن لیست قالب‌ها
      templateSelect.innerHTML = '<option value="">قالب را انتخاب کنید</option>';
      departmentTemplates[department].forEach(template => {
        const option = document.createElement('option');
        option.value = template.id;
        option.textContent = template.name;
        option.dataset.fields = JSON.stringify(template.fields);
        templateSelect.appendChild(option);
      });
      
      // مخفی کردن بقیه
      commonFieldsSection.style.display = 'none';
      customFieldsSection.style.display = 'none';
      actionButtonsSection.style.display = 'none';
    } else {
      templateSelectContainer.style.display = 'none';
      commonFieldsSection.style.display = 'none';
      customFieldsSection.style.display = 'none';
      actionButtonsSection.style.display = 'none';
    }
  });
  
  // انتخاب قالب
  templateSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (this.value) {
      // نمایش فیلدهای مشترک
      commonFieldsSection.style.display = '';
      actionButtonsSection.style.display = '';
      
      // بارگذاری فیلدهای سفارشی
      const fields = JSON.parse(selectedOption.dataset.fields || '[]');
      
      if (fields.length > 0) {
        customFieldsSection.style.display = '';
        customFieldsContainer.innerHTML = '';
        
        fields.forEach(field => {
          const fieldHtml = generateFieldHtml(field);
          customFieldsContainer.insertAdjacentHTML('beforeend', fieldHtml);
        });
      } else {
        customFieldsSection.style.display = 'none';
      }
    } else {
      commonFieldsSection.style.display = 'none';
      customFieldsSection.style.display = 'none';
      actionButtonsSection.style.display = 'none';
    }
  });
  
  // تولید HTML فیلدها
  function generateFieldHtml(field) {
    const required = field.required ? '<span class="text-red-500 mr-1">*</span>' : '';
    const requiredAttr = field.required ? 'required' : '';
    
    if (field.type === 'textarea') {
      return `
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex">
            <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary leading-normal">
              ${field.label} ${required}
            </label>
            <textarea rows="4" name="custom[${field.name}]" ${requiredAttr}
                      class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none resize-none bg-transparent leading-relaxed"
                      placeholder="${field.label}"></textarea>
          </div>
        </div>
      `;
    } else if (field.type === 'select') {
      const options = field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
      return `
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              ${field.label} ${required}
            </label>
            <select name="custom[${field.name}]" ${requiredAttr} class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
              <option value="">انتخاب کنید</option>
              ${options}
            </select>
          </div>
        </div>
      `;
    } else if (field.type === 'radio') {
      const radios = field.options.map(opt => `
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="custom[${field.name}]" value="${opt}" ${requiredAttr} class="w-4 h-4 text-teal-600 accent-teal-600">
          <span class="text-base text-text-primary leading-normal">${opt}</span>
        </label>
      `).join('');
      return `
        <div class="border border-border-medium rounded-xl p-4">
          <label class="block text-sm text-text-secondary mb-3 leading-normal">${field.label} ${required}</label>
          <div class="flex flex-wrap items-center gap-4">
            ${radios}
          </div>
        </div>
      `;
    } else if (field.type === 'checkbox') {
      const checkboxes = field.options.map(opt => `
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="custom[${field.name}][]" value="${opt}" class="w-4 h-4 text-teal-600 accent-teal-600">
          <span class="text-sm text-text-primary leading-normal">${opt}</span>
        </label>
      `).join('');
      return `
        <div class="border border-border-medium rounded-xl p-4">
          <label class="block text-sm text-text-secondary mb-3 leading-normal">${field.label} ${required}</label>
          <div class="flex flex-wrap items-center gap-3">
            ${checkboxes}
          </div>
        </div>
      `;
    } else {
      // text, number, date
      const inputType = field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : 'text';
      return `
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-teal-600 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[140px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              ${field.label} ${required}
            </label>
            <input type="${inputType}" name="custom[${field.name}]" ${requiredAttr}
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                   placeholder="${field.label}">
          </div>
        </div>
      `;
    }
  }
  </script>
  
</body>
</html>


