<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle   = 'مدیریت لیست‌ها';
$currentPage = 'lists-index';

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'لیست‌ها'],
];

$actionButtons = [
    ['label' => 'ایجاد لیست جدید', 'onclick' => 'openCreateListModal()', 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
];

// آیکون‌های پیشنهادی برای لیست‌ها
$suggestedIcons = [
    'fa-solid fa-truck', 'fa-solid fa-warehouse', 'fa-solid fa-industry', 'fa-solid fa-building',
    'fa-solid fa-tools', 'fa-solid fa-wrench', 'fa-solid fa-cogs', 'fa-solid fa-desktop',
    'fa-solid fa-laptop', 'fa-solid fa-server', 'fa-solid fa-hard-drive', 'fa-solid fa-print',
    'fa-solid fa-chair', 'fa-solid fa-couch', 'fa-solid fa-car', 'fa-solid fa-motorcycle',
    'fa-solid fa-dolly', 'fa-solid fa-pallet', 'fa-solid fa-box-open', 'fa-solid fa-boxes-stacked',
    'fa-solid fa-flask', 'fa-solid fa-vial', 'fa-solid fa-microscope', 'fa-solid fa-stethoscope',
    'fa-solid fa-pills', 'fa-solid fa-syringe', 'fa-solid fa-shield-halved', 'fa-solid fa-fire-extinguisher',
    'fa-solid fa-helmet-safety', 'fa-solid fa-vest', 'fa-solid fa-utensils', 'fa-solid fa-mug-hot',
    'fa-solid fa-blender', 'fa-solid fa-kitchen-set', 'fa-solid fa-broom', 'fa-solid fa-spray-can-sparkles',
    'fa-solid fa-plug', 'fa-solid fa-bolt', 'fa-solid fa-battery-full', 'fa-solid fa-solar-panel',
    'fa-solid fa-fan', 'fa-solid fa-temperature-half', 'fa-solid fa-lightbulb', 'fa-solid fa-faucet-drip',
    'fa-solid fa-hammer', 'fa-solid fa-screwdriver-wrench', 'fa-solid fa-tape', 'fa-solid fa-ruler',
    'fa-solid fa-paintbrush', 'fa-solid fa-scissors',
];

// داده‌های نمونه لیست‌ها
$lists = [
    [
        'id'           => 1,
        'name'         => 'تأمین‌کنندگان',
        'name_en'      => 'suppliers',
        'icon'         => 'fa-solid fa-truck',
        'items_count'  => 45,
        'color'        => 'blue',
        'field1_label' => 'شماره تماس', 'field1_type' => 'text',
        'field2_label' => 'آدرس', 'field2_type' => 'text',
        'field3_label' => 'امتیاز', 'field3_type' => 'number',
        'field4_label' => '', 'field4_type' => '',
        'field5_label' => '', 'field5_type' => '',
        'created_at'   => '۵ بهمن ۱۴۰۴',
    ],
    [
        'id'           => 2,
        'name'         => 'ملزومات تولید',
        'name_en'      => 'production-materials',
        'icon'         => 'fa-solid fa-industry',
        'items_count'  => 128,
        'color'        => 'green',
        'field1_label' => 'واحد', 'field1_type' => 'text',
        'field2_label' => 'موجودی', 'field2_type' => 'number',
        'field3_label' => 'حداقل سفارش', 'field3_type' => 'number',
        'field4_label' => 'تأمین‌کننده', 'field4_type' => 'text',
        'field5_label' => '', 'field5_type' => '',
        'created_at'   => '۳ بهمن ۱۴۰۴',
    ],
    [
        'id'           => 3,
        'name'         => 'دستگاه‌ها و تجهیزات',
        'name_en'      => 'equipment',
        'icon'         => 'fa-solid fa-cogs',
        'items_count'  => 32,
        'color'        => 'purple',
        'field1_label' => 'شماره سریال', 'field1_type' => 'text',
        'field2_label' => 'محل نصب', 'field2_type' => 'text',
        'field3_label' => 'تاریخ خرید', 'field3_type' => 'date',
        'field4_label' => 'وضعیت', 'field4_type' => 'text',
        'field5_label' => 'ارزش (ریال)', 'field5_type' => 'number',
        'created_at'   => '۱ بهمن ۱۴۰۴',
    ],
    [
        'id'           => 4,
        'name'         => 'اموال و دارایی‌ها',
        'name_en'      => 'assets',
        'icon'         => 'fa-solid fa-building',
        'items_count'  => 87,
        'color'        => 'orange',
        'field1_label' => 'کد اموال', 'field1_type' => 'text',
        'field2_label' => 'محل استقرار', 'field2_type' => 'text',
        'field3_label' => 'تحویل‌گیرنده', 'field3_type' => 'text',
        'field4_label' => 'ارزش (ریال)', 'field4_type' => 'number',
        'field5_label' => 'تاریخ خرید', 'field5_type' => 'date',
        'created_at'   => '۲۸ دی ۱۴۰۴',
    ],
    [
        'id'           => 5,
        'name'         => 'ابزارآلات',
        'name_en'      => 'tools',
        'icon'         => 'fa-solid fa-wrench',
        'items_count'  => 65,
        'color'        => 'teal',
        'field1_label' => 'تعداد', 'field1_type' => 'number',
        'field2_label' => 'محل نگهداری', 'field2_type' => 'text',
        'field3_label' => '', 'field3_type' => '',
        'field4_label' => '', 'field4_type' => '',
        'field5_label' => '', 'field5_type' => '',
        'created_at'   => '۲۵ دی ۱۴۰۴',
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  
  <div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php component('products-sidebar', ['currentPage' => $currentPage]); ?>
    
    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      
      <!-- Module Header -->
      <?php component('module-header', [
          'pageTitle'     => $pageTitle,
          'breadcrumbs'   => $breadcrumbs,
          'actionButtons' => $actionButtons,
      ]); ?>
      
      <!-- Content Area -->
      <div class="flex-1 p-6 lg:p-8">
      
      <!-- کارت‌های لیست‌ها -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <?php
        $cardColors = [
            'blue'   => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600', 'border' => 'hover:border-blue-200'],
            'green'  => ['bg' => 'bg-green-50', 'icon' => 'text-green-600', 'border' => 'hover:border-green-200'],
            'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'border' => 'hover:border-purple-200'],
            'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600', 'border' => 'hover:border-orange-200'],
            'teal'   => ['bg' => 'bg-teal-50', 'icon' => 'text-teal-600', 'border' => 'hover:border-teal-200'],
            'red'    => ['bg' => 'bg-red-50', 'icon' => 'text-red-600', 'border' => 'hover:border-red-200'],
            'pink'   => ['bg' => 'bg-pink-50', 'icon' => 'text-pink-600', 'border' => 'hover:border-pink-200'],
            'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'border' => 'hover:border-indigo-200'],
            'amber'  => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'border' => 'hover:border-amber-200'],
            'cyan'   => ['bg' => 'bg-cyan-50', 'icon' => 'text-cyan-600', 'border' => 'hover:border-cyan-200'],
            'lime'   => ['bg' => 'bg-lime-50', 'icon' => 'text-lime-600', 'border' => 'hover:border-lime-200'],
            'rose'   => ['bg' => 'bg-rose-50', 'icon' => 'text-rose-600', 'border' => 'hover:border-rose-200'],
        ];
?>
        
        <?php foreach ($lists as $list): ?>
          <?php $colors = $cardColors[$list['color']] ?? $cardColors['blue']; ?>
          <a href="/dashboard/products/lists/items.php?id=<?= $list['id'] ?>" 
             class="group bg-bg-primary border border-border-light rounded-2xl p-6 hover:shadow-md <?= $colors['border'] ?> transition-all duration-200 block">
            <div class="flex items-start gap-4 mb-4">
              <div class="w-12 h-12 <?= $colors['bg'] ?> rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <i class="<?= $list['icon'] ?> text-xl <?= $colors['icon'] ?>"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-text-primary leading-snug mb-1 truncate"><?= $list['name'] ?></h3>
                <p class="text-xs text-text-muted font-mono leading-normal"><?= $list['name_en'] ?></p>
              </div>
              <div class="flex items-center gap-1">
                <button onclick="event.preventDefault(); openEditListModal(<?= $list['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش لیست">
                  <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <button onclick="event.preventDefault(); deleteList(<?= $list['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف لیست">
                  <i class="fa-solid fa-trash text-xs"></i>
                </button>
              </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-border-light">
              <span class="text-sm text-text-muted leading-normal">
                <i class="fa-solid fa-list ml-1"></i>
                <?= toPersianNum($list['items_count']) ?> آیتم
              </span>
              <span class="text-xs text-text-muted leading-normal"><?= $list['created_at'] ?></span>
            </div>
          </a>
        <?php endforeach; ?>
        
        <!-- کارت افزودن لیست جدید -->
        <button onclick="openCreateListModal()" class="bg-bg-primary border-2 border-dashed border-border-medium rounded-2xl p-2 hover:border-primary hover:bg-primary/5 transition-all duration-200 flex flex-col items-center justify-center text-center min-h-[140px] group">
          <div class="w-12 h-12 bg-bg-secondary rounded-full flex items-center justify-center mb-2 group-hover:bg-primary/10 transition-colors duration-200">
            <i class="fa-solid fa-plus text-2xl text-text-muted group-hover:text-primary transition-colors duration-200"></i>
          </div>
          <span class="text-base font-semibold text-text-secondary leading-normal group-hover:text-primary transition-colors duration-200">ایجاد لیست جدید</span>
        </button>
        
      </div>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- مودال ایجاد/ویرایش لیست -->
  <div id="listModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl w-full max-w-[750px] shadow-2xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-border-light flex items-center justify-between sticky top-0 bg-white rounded-t-3xl z-10">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-clipboard-list text-primary"></i>
          </div>
          <div>
            <h3 id="listModalTitle" class="text-lg font-bold text-text-primary leading-snug">ایجاد لیست جدید</h3>
            <p class="text-sm text-text-muted leading-normal">اطلاعات لیست را وارد کنید</p>
          </div>
        </div>
        <button onclick="closeListModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      
      <!-- Body -->
      <div class="p-6 space-y-6">
        
        <!-- اطلاعات پایه -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <!-- نام لیست -->
          <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                نام لیست <span class="text-red-500 mr-1">*</span>
              </label>
              <input type="text" id="listName" required
                     class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                     placeholder="مثال: تأمین‌کنندگان">
            </div>
          </div>
          
          <!-- نام انگلیسی -->
          <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
            <div class="flex items-stretch">
              <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                نام انگلیسی <span class="text-red-500 mr-1">*</span>
              </label>
              <input type="text" id="listNameEn" required dir="ltr"
                     class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                     placeholder="suppliers">
            </div>
          </div>
        </div>
        
        <!-- انتخاب رنگ -->
        <div>
          <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
            <i class="fa-solid fa-palette text-slate-400 ml-2"></i>
            رنگ لیست
          </label>
          <input type="hidden" id="selectedColor" value="blue">
          <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary">
            <div class="flex flex-wrap gap-3" id="colorGrid">
              <?php
      $listColors = [
          ['value' => 'blue', 'bg' => 'bg-blue-500'],
          ['value' => 'green', 'bg' => 'bg-green-500'],
          ['value' => 'purple', 'bg' => 'bg-purple-500'],
          ['value' => 'orange', 'bg' => 'bg-orange-500'],
          ['value' => 'teal', 'bg' => 'bg-teal-500'],
          ['value' => 'red', 'bg' => 'bg-red-500'],
          ['value' => 'pink', 'bg' => 'bg-pink-500'],
          ['value' => 'indigo', 'bg' => 'bg-indigo-500'],
          ['value' => 'amber', 'bg' => 'bg-amber-500'],
          ['value' => 'cyan', 'bg' => 'bg-cyan-500'],
          ['value' => 'lime', 'bg' => 'bg-lime-500'],
          ['value' => 'rose', 'bg' => 'bg-rose-500'],
      ];

foreach ($listColors as $color):
    ?>
              <button type="button" onclick="selectColor(this, '<?= $color['value'] ?>')"
                      class="color-btn w-9 h-9 <?= $color['bg'] ?> rounded-lg border-2 <?= $color['value'] === 'blue' ? 'border-slate-900 ring-2 ring-slate-900/20' : 'border-transparent' ?> hover:scale-110 transition-all duration-200"
                      data-color="<?= $color['value'] ?>">
                <?php if ($color['value'] === 'blue'): ?>
                  <i class="fa-solid fa-check text-white text-xs"></i>
                <?php endif; ?>
              </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- انتخاب آیکون -->
        <div>
          <label class="block text-sm font-medium text-text-secondary mb-3 leading-normal">
            <i class="fa-solid fa-icons text-slate-400 ml-2"></i>
            آیکون لیست
          </label>
          <input type="hidden" id="selectedIcon" value="fa-solid fa-clipboard-list">
          <div class="border border-border-medium rounded-xl p-4 bg-bg-tertiary">
            <div class="grid grid-cols-10 sm:grid-cols-13 gap-2" id="iconGrid">
              <?php foreach ($suggestedIcons as $icon): ?>
              <button type="button" onclick="selectIcon(this, '<?= $icon ?>')" 
                      class="icon-btn w-10 h-10 flex items-center justify-center rounded-lg border border-transparent hover:border-primary hover:bg-primary/10 transition-all duration-200 text-text-secondary hover:text-primary"
                      data-icon="<?= $icon ?>">
                <i class="<?= $icon ?>"></i>
              </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <!-- فیلدهای سفارشی (حداکثر ۵) -->
        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-medium text-text-secondary leading-normal">
              <i class="fa-solid fa-sliders text-slate-400 ml-2"></i>
              فیلدهای اختیاری
              <span class="text-text-muted text-xs">(حداکثر ۵ فیلد)</span>
            </label>
          </div>
          <p class="text-xs text-text-muted mb-4 leading-relaxed">
            <i class="fa-solid fa-info-circle ml-1"></i>
            برای هر فیلد یک لیبل (عنوان نمایشی) و نوع فیلد انتخاب کنید. فیلدهای خالی نادیده گرفته می‌شوند.
          </p>
          
          <div class="space-y-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <?php
    $persianNums            = ['۱','۲','۳','۴','۵'];
                $fieldLabel = 'فیلد ' . $persianNums[$i - 1];
                ?>
            <div class="flex items-stretch gap-3">
              <div class="flex-1 border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3 text-sm text-text-secondary flex items-center leading-normal">
                    <?= $fieldLabel ?>
                  </label>
                  <input type="text" id="field<?= $i ?>_label"
                         class="flex-1 px-lg py-3 text-base text-text-primary outline-none bg-transparent leading-normal"
                         placeholder="عنوان فیلد">
                </div>
              </div>
              <div class="w-[160px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[50px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                    نوع
                  </label>
                  <select id="field<?= $i ?>_type" class="flex-1 px-3 py-3 text-sm text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                    <option value="text" selected>متن</option>
                    <option value="number">عدد</option>
                    <option value="date">تاریخ</option>
                    <option value="select">انتخابی</option>
                    <option value="textarea">متن بلند</option>
                  </select>
                </div>
              </div>
            </div>
            <?php endfor; ?>
          </div>
        </div>
        
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 sticky bottom-0 bg-bg-secondary rounded-b-3xl">
        <button onclick="closeListModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button onclick="saveList()" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
          <i class="fa-solid fa-check ml-2"></i>
          ذخیره لیست
        </button>
      </div>
    </div>
  </div>
  
  <!-- مودال حذف لیست -->
  <div id="deleteListModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
      <div class="p-6 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-trash text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-text-primary leading-snug mb-2">حذف لیست</h3>
        <p class="text-base text-text-secondary leading-relaxed mb-6">آیا از حذف این لیست و همه آیتم‌های آن اطمینان دارید؟ این عملیات قابل بازگشت نیست.</p>
        <div class="flex items-center justify-center gap-3">
          <button onclick="closeDeleteListModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-base leading-normal">
            انصراف
          </button>
          <button onclick="confirmDeleteList()" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-trash ml-2"></i>
            حذف لیست
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    let editingListId = null;
    let deleteListId = null;
    
    // انتخاب رنگ
    function selectColor(btn, color) {
      document.querySelectorAll('.color-btn').forEach(b => {
        b.classList.remove('border-slate-900', 'ring-2', 'ring-slate-900/20');
        b.classList.add('border-transparent');
        b.innerHTML = '';
      });
      btn.classList.remove('border-transparent');
      btn.classList.add('border-slate-900', 'ring-2', 'ring-slate-900/20');
      btn.innerHTML = '<i class="fa-solid fa-check text-white text-xs"></i>';
      document.getElementById('selectedColor').value = color;
    }
    
    // انتخاب آیکون
    function selectIcon(btn, icon) {
      document.querySelectorAll('.icon-btn').forEach(b => {
        b.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
        b.classList.add('border-transparent');
      });
      btn.classList.remove('border-transparent');
      btn.classList.add('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
      document.getElementById('selectedIcon').value = icon;
    }
    
    // باز کردن مودال ایجاد لیست
    function openCreateListModal() {
      editingListId = null;
      document.getElementById('listModalTitle').textContent = 'ایجاد لیست جدید';
      document.getElementById('listName').value = '';
      document.getElementById('listNameEn').value = '';
      document.getElementById('selectedIcon').value = 'fa-solid fa-clipboard-list';
      
      // ریست فیلدهای سفارشی
      for (let i = 1; i <= 5; i++) {
        document.getElementById('field' + i + '_label').value = '';
        document.getElementById('field' + i + '_type').value = 'text';
      }
      
      // ریست رنگ
      document.querySelectorAll('.color-btn').forEach(b => {
        b.classList.remove('border-slate-900', 'ring-2', 'ring-slate-900/20');
        b.classList.add('border-transparent');
        b.innerHTML = '';
      });
      const defaultColorBtn = document.querySelector('.color-btn[data-color="blue"]');
      if (defaultColorBtn) {
        defaultColorBtn.classList.remove('border-transparent');
        defaultColorBtn.classList.add('border-slate-900', 'ring-2', 'ring-slate-900/20');
        defaultColorBtn.innerHTML = '<i class="fa-solid fa-check text-white text-xs"></i>';
      }
      document.getElementById('selectedColor').value = 'blue';
      
      // ریست آیکون‌ها
      document.querySelectorAll('.icon-btn').forEach(b => {
        b.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'ring-2', 'ring-primary/30');
        b.classList.add('border-transparent');
      });
      
      document.getElementById('listModal').classList.remove('hidden');
      document.getElementById('listModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    // ویرایش لیست
    function openEditListModal(id) {
      editingListId = id;
      document.getElementById('listModalTitle').textContent = 'ویرایش لیست';
      
      // در پروژه واقعی اطلاعات از سرور لود می‌شود
      document.getElementById('listModal').classList.remove('hidden');
      document.getElementById('listModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    // بستن مودال لیست
    function closeListModal() {
      document.getElementById('listModal').classList.add('hidden');
      document.getElementById('listModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      editingListId = null;
    }
    
    // ذخیره لیست
    function saveList() {
      const name = document.getElementById('listName').value.trim();
      const nameEn = document.getElementById('listNameEn').value.trim();
      
      if (!name || !nameEn) {
        alert('لطفاً نام و نام انگلیسی لیست را وارد کنید');
        return;
      }
      
      const data = {
        name: name,
        name_en: nameEn,
        icon: document.getElementById('selectedIcon').value,
        color: document.getElementById('selectedColor').value,
        fields: []
      };
      
      for (let i = 1; i <= 5; i++) {
        const label = document.getElementById('field' + i + '_label').value.trim();
        const type = document.getElementById('field' + i + '_type').value;
        if (label && type) {
          data.fields.push({ label, type });
        }
      }
      
      console.log(editingListId ? 'Updating list:' : 'Creating list:', data);
      alert(editingListId ? 'لیست با موفقیت ویرایش شد' : 'لیست با موفقیت ایجاد شد');
      closeListModal();
    }
    
    // حذف لیست
    function deleteList(id) {
      deleteListId = id;
      document.getElementById('deleteListModal').classList.remove('hidden');
      document.getElementById('deleteListModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteListModal() {
      document.getElementById('deleteListModal').classList.add('hidden');
      document.getElementById('deleteListModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      deleteListId = null;
    }
    
    function confirmDeleteList() {
      console.log('Deleting list:', deleteListId);
      alert('لیست با موفقیت حذف شد');
      closeDeleteListModal();
    }
    
    // بستن مودال‌ها با کلیک بیرون
    document.getElementById('listModal').addEventListener('click', function(e) {
      if (e.target === this) closeListModal();
    });
    document.getElementById('deleteListModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteListModal();
    });
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (!document.getElementById('listModal').classList.contains('hidden')) closeListModal();
        if (!document.getElementById('deleteListModal').classList.contains('hidden')) closeDeleteListModal();
      }
    });
  </script>
  
</body>
</html>
