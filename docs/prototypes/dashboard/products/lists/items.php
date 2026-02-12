<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// شناسه لیست
$listId = $_GET['id'] ?? 1;

// تنظیمات صفحه
$currentPage = 'lists-index';

// داده نمونه لیست (در پروژه واقعی از دیتابیس)
$list = [
    'id'           => 1,
    'name'         => 'تأمین‌کنندگان',
    'name_en'      => 'suppliers',
    'icon'         => 'fa-solid fa-truck',
    'field1_label' => 'شماره تماس', 'field1_type' => 'text',
    'field2_label' => 'آدرس', 'field2_type' => 'text',
    'field3_label' => 'امتیاز', 'field3_type' => 'number',
    'field4_label' => '', 'field4_type' => '',
    'field5_label' => '', 'field5_type' => '',
];

$pageTitle = $list['name'];

// فیلدهای فعال
$activeFields = [];
for ($i = 1; $i <= 5; $i++) {
    if (!empty($list['field' . $i . '_label'])) {
        $activeFields[] = [
            'num'   => $i,
            'label' => $list['field' . $i . '_label'],
            'type'  => $list['field' . $i . '_type'],
        ];
    }
}

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'محصولات و لیست‌ها', 'url' => '/dashboard/products/index.php'],
    ['label' => 'لیست‌ها', 'url' => '/dashboard/products/lists/index.php'],
    ['label' => $list['name']],
];

$actionButtons = [
    ['label' => 'بازگشت به لیست‌ها', 'url' => '/dashboard/products/lists/index.php', 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
];

// داده‌های نمونه آیتم‌ها
$items = [
    ['id' => 1, 'title' => 'شرکت آلفا تأمین', 'code' => 'SUP-۰۰۱', 'field1' => '۰۲۱-۸۸۷۷۶۶۵۵', 'field2' => 'تهران، خیابان ولیعصر', 'field3' => '۹.۲', 'is_active' => true],
    ['id' => 2, 'title' => 'صنایع بتا', 'code' => 'SUP-۰۰۲', 'field1' => '۰۳۱-۳۳۴۴۵۵۶۶', 'field2' => 'اصفهان، شهرک صنعتی', 'field3' => '۸.۵', 'is_active' => true],
    ['id' => 3, 'title' => 'گروه تولیدی گاما', 'code' => 'SUP-۰۰۳', 'field1' => '۰۲۱-۴۴۵۵۶۶۷۷', 'field2' => 'تهران، شهرک غرب', 'field3' => '۷.۸', 'is_active' => true],
    ['id' => 4, 'title' => 'بازرگانی دلتا', 'code' => 'SUP-۰۰۴', 'field1' => '۰۵۱-۳۵۷۸۹۰۱۲', 'field2' => 'مشهد، بلوار وکیل‌آباد', 'field3' => '۹.۰', 'is_active' => true],
    ['id' => 5, 'title' => 'شرکت اپسیلون', 'code' => 'SUP-۰۰۵', 'field1' => '۰۲۱-۲۲۳۳۴۴۵۵', 'field2' => 'تهران، سعادت‌آباد', 'field3' => '۶.۵', 'is_active' => false],
    ['id' => 6, 'title' => 'صنایع زتا', 'code' => 'SUP-۰۰۶', 'field1' => '۰۴۱-۳۳۲۲۱۱۰۰', 'field2' => 'تبریز، جاده صنعتی', 'field3' => '۸.۰', 'is_active' => true],
    ['id' => 7, 'title' => 'تجارت اتا', 'code' => 'SUP-۰۰۷', 'field1' => '۰۷۱-۳۶۲۵۱۴۰۳', 'field2' => 'شیراز، بلوار پاسداران', 'field3' => '۷.۴', 'is_active' => true],
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
      
      <!-- هدر لیست و دکمه‌های ایمپورت/اکسپورت -->
      <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
              <i class="<?= $list['icon'] ?> text-xl text-blue-600"></i>
            </div>
            <div>
              <h2 class="text-xl font-bold text-text-primary leading-snug"><?= $list['name'] ?></h2>
              <p class="text-sm font-mono text-text-muted leading-normal mt-1"><?= $list['name_en'] ?></p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <!-- ایمپورت -->
            <div class="relative" data-dropdown-container>
              <button onclick="toggleImportDropdown()" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                <i class="fa-solid fa-file-import"></i>
                <span>ایمپورت</span>
                <i class="fa-solid fa-chevron-down text-xs"></i>
              </button>
              <div id="importDropdown" class="hidden absolute left-0 top-full mt-2 w-64 bg-white border border-border-light rounded-xl shadow-lg z-20 overflow-hidden">
                <div class="p-3 border-b border-border-light">
                  <p class="text-xs text-text-muted leading-relaxed">فایل اکسل با فرمت استاندارد این لیست آپلود کنید</p>
                </div>
                <button onclick="downloadTemplate()" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-text-secondary hover:bg-bg-secondary transition-colors leading-normal">
                  <i class="fa-solid fa-download text-green-600"></i>
                  <span>دانلود قالب اکسل</span>
                </button>
                <button onclick="document.getElementById('importFile').click()" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-text-secondary hover:bg-bg-secondary transition-colors leading-normal border-t border-border-light">
                  <i class="fa-solid fa-upload text-blue-600"></i>
                  <span>آپلود فایل اکسل</span>
                </button>
                <input type="file" id="importFile" accept=".xlsx,.xls,.csv" class="hidden" onchange="handleImport(this)">
              </div>
            </div>
            
            <!-- اکسپورت -->
            <button onclick="exportList()" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
              <i class="fa-solid fa-file-export"></i>
              <span>اکسپورت</span>
            </button>
          </div>
        </div>
      </div>
      
      <!-- فرم افزودن آیتم (Inline) -->
      <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
        <h3 class="text-base font-semibold text-text-primary leading-snug mb-4">
          <i class="fa-solid fa-plus-circle text-primary ml-2"></i>
          افزودن آیتم جدید
        </h3>
        <form id="addItemForm" onsubmit="addItem(event)">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <!-- عنوان آیتم -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[80px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                  عنوان <span class="text-red-500 mr-1">*</span>
                </label>
                <input type="text" id="newItemTitle" required
                       class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="عنوان آیتم">
              </div>
            </div>
            
            <!-- کد -->
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[50px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                  کد
                </label>
                <input type="text" id="newItemCode"
                       class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="کد">
              </div>
            </div>
            
            <!-- فیلدهای سفارشی -->
            <?php foreach ($activeFields as $field): ?>
            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light min-w-[80px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                  <?= $field['label'] ?>
                </label>
                <input type="<?= $field['type'] === 'number' ? 'number' : 'text' ?>" id="newItemField<?= $field['num'] ?>"
                       class="flex-1 px-3 py-3 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="<?= $field['label'] ?>">
              </div>
            </div>
            <?php endforeach; ?>
            
            <!-- دکمه افزودن -->
            <div class="flex items-stretch">
              <button type="submit" class="bg-primary text-white px-xl py-3 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal whitespace-nowrap flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>افزودن</span>
              </button>
            </div>
            
          </div>
        </form>
      </div>
      
      <!-- جدول آیتم‌ها -->
      <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
        <!-- جستجو -->
        <div class="px-6 py-4 border-b border-border-light">
          <div class="flex items-center gap-4">
            <div class="flex-1 max-w-md border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
              <div class="flex items-stretch">
                <label class="bg-bg-label border-l border-border-light px-3 py-2.5 text-sm text-text-secondary flex items-center leading-normal">
                  <i class="fa-solid fa-search"></i>
                </label>
                <input type="text" 
                       class="flex-1 px-3 py-2.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                       placeholder="جستجو در آیتم‌ها...">
              </div>
            </div>
            <span class="text-sm text-text-muted leading-normal mr-auto">
              <?= toPersianNum(count($items)) ?> آیتم
            </span>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-bg-secondary border-b border-border-light">
              <tr>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">ردیف</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">عنوان</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">کد</th>
                <?php foreach ($activeFields as $field): ?>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal"><?= $field['label'] ?></th>
                <?php endforeach; ?>
                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $index => $item): ?>
                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200" id="item-row-<?= $item['id'] ?>">
                  <td class="px-6 py-4 text-sm text-text-muted leading-normal"><?= toPersianNum($index + 1) ?></td>
                  <td class="px-6 py-4 text-base text-text-primary font-medium leading-normal"><?= $item['title'] ?></td>
                  <td class="px-6 py-4 text-sm text-text-secondary font-mono leading-normal"><?= $item['code'] ?></td>
                  <?php foreach ($activeFields as $field): ?>
                  <td class="px-6 py-4 text-base text-text-secondary leading-normal"><?= $item['field' . $field['num']] ?? '-' ?></td>
                  <?php endforeach; ?>
                  <td class="px-6 py-4">
                    <?php if ($item['is_active']): ?>
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
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <button onclick="editItem(<?= $item['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                        <i class="fa-solid fa-pen"></i>
                      </button>
                      <button onclick="deleteItem(<?= $item['id'] ?>)" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" title="حذف">
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
          <div class="text-sm text-text-secondary leading-normal">
            نمایش <span class="font-semibold text-text-primary">۱</span> تا <span class="font-semibold text-text-primary">۷</span> از <span class="font-semibold text-text-primary">۴۵</span> آیتم
          </div>
          <div class="flex items-center gap-2">
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
              <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium">۱</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۲</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۳</button>
            <span class="px-2 text-text-muted">...</span>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">۷</button>
            <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
          </div>
        </div>
      </div>
      
      </div>
      
    </main>
    
  </div>
  
  <!-- مودال ویرایش آیتم -->
  <div id="editItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
        <h3 class="text-lg font-bold text-text-primary leading-snug">ویرایش آیتم</h3>
        <button onclick="closeEditItemModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      
      <!-- Body -->
      <div class="p-6 space-y-4">
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              عنوان <span class="text-red-500 mr-1">*</span>
            </label>
            <input type="text" id="editItemTitle"
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
          </div>
        </div>
        
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              کد
            </label>
            <input type="text" id="editItemCode"
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
          </div>
        </div>
        
        <?php foreach ($activeFields as $field): ?>
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[110px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              <?= $field['label'] ?>
            </label>
            <input type="<?= $field['type'] === 'number' ? 'number' : 'text' ?>" id="editItemField<?= $field['num'] ?>"
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal">
          </div>
        </div>
        <?php endforeach; ?>
        
        <!-- وضعیت -->
        <div>
          <label class="block text-sm text-text-secondary mb-3 leading-normal">وضعیت</label>
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="edit_status" value="active" checked class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">فعال</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="edit_status" value="inactive" class="w-4 h-4 text-primary accent-primary">
              <span class="text-base text-text-primary leading-normal">غیرفعال</span>
            </label>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 bg-bg-secondary rounded-b-2xl">
        <button onclick="closeEditItemModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-white transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button onclick="saveEditItem()" class="px-6 py-2.5 bg-primary text-white rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal">
          <i class="fa-solid fa-check ml-2"></i>
          ذخیره تغییرات
        </button>
      </div>
    </div>
  </div>
  
  <!-- مودال حذف آیتم -->
  <div id="deleteItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
      <div class="p-6 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-trash text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-text-primary leading-snug mb-2">حذف آیتم</h3>
        <p class="text-base text-text-secondary leading-relaxed mb-6">آیا از حذف این آیتم اطمینان دارید؟</p>
        <div class="flex items-center justify-center gap-3">
          <button onclick="closeDeleteItemModal()" class="px-6 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-base leading-normal">
            انصراف
          </button>
          <button onclick="confirmDeleteItem()" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 text-base leading-normal">
            <i class="fa-solid fa-trash ml-2"></i>
            حذف
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <script>
    let editingItemId = null;
    let deletingItemId = null;
    
    // افزودن آیتم
    function addItem(e) {
      e.preventDefault();
      const title = document.getElementById('newItemTitle').value.trim();
      if (!title) return;
      
      const data = {
        title: title,
        code: document.getElementById('newItemCode').value.trim(),
      };
      
      <?php foreach ($activeFields as $field): ?>
      data['field<?= $field['num'] ?>'] = document.getElementById('newItemField<?= $field['num'] ?>').value.trim();
      <?php endforeach; ?>
      
      console.log('Adding item:', data);
      alert('آیتم با موفقیت اضافه شد');
      
      // ریست فرم
      document.getElementById('addItemForm').reset();
    }
    
    // ویرایش آیتم
    function editItem(id) {
      editingItemId = id;
      // در پروژه واقعی اطلاعات از سرور لود می‌شود
      document.getElementById('editItemTitle').value = 'آیتم نمونه';
      document.getElementById('editItemCode').value = 'SUP-00' + id;
      
      document.getElementById('editItemModal').classList.remove('hidden');
      document.getElementById('editItemModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    function closeEditItemModal() {
      document.getElementById('editItemModal').classList.add('hidden');
      document.getElementById('editItemModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      editingItemId = null;
    }
    
    function saveEditItem() {
      console.log('Saving item:', editingItemId);
      alert('آیتم با موفقیت ویرایش شد');
      closeEditItemModal();
    }
    
    // حذف آیتم
    function deleteItem(id) {
      deletingItemId = id;
      document.getElementById('deleteItemModal').classList.remove('hidden');
      document.getElementById('deleteItemModal').classList.add('flex');
      document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteItemModal() {
      document.getElementById('deleteItemModal').classList.add('hidden');
      document.getElementById('deleteItemModal').classList.remove('flex');
      document.body.style.overflow = 'auto';
      deletingItemId = null;
    }
    
    function confirmDeleteItem() {
      console.log('Deleting item:', deletingItemId);
      alert('آیتم با موفقیت حذف شد');
      closeDeleteItemModal();
    }
    
    // ایمپورت/اکسپورت
    function toggleImportDropdown() {
      const dropdown = document.getElementById('importDropdown');
      dropdown.classList.toggle('hidden');
    }
    
    function downloadTemplate() {
      // در پروژه واقعی فایل اکسل با ساختار لیست دانلود می‌شود
      alert('قالب اکسل دانلود شد. ستون‌ها شامل: عنوان، کد<?php foreach ($activeFields as $field): ?>، <?= $field['label'] ?><?php endforeach; ?>');
      document.getElementById('importDropdown').classList.add('hidden');
    }
    
    function handleImport(input) {
      if (input.files.length > 0) {
        const file = input.files[0];
        console.log('Importing file:', file.name);
        alert('فایل "' + file.name + '" با موفقیت ایمپورت شد');
      }
      document.getElementById('importDropdown').classList.add('hidden');
    }
    
    function exportList() {
      // در پروژه واقعی فایل اکسل با داده‌ها دانلود می‌شود
      alert('فایل اکسل لیست "<?= $list['name'] ?>" دانلود شد');
    }
    
    // بستن dropdown ایمپورت با کلیک بیرون
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById('importDropdown');
      if (!e.target.closest('[data-dropdown-container]') && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    });
    
    // بستن مودال‌ها
    document.getElementById('editItemModal').addEventListener('click', function(e) {
      if (e.target === this) closeEditItemModal();
    });
    document.getElementById('deleteItemModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteItemModal();
    });
    
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (!document.getElementById('editItemModal').classList.contains('hidden')) closeEditItemModal();
        if (!document.getElementById('deleteItemModal').classList.contains('hidden')) closeDeleteItemModal();
      }
    });
  </script>
  
</body>
</html>
