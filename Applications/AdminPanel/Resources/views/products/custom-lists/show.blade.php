@php
    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'محصولات و لیست‌ها', 'url' => route('web.product.dashboard')],
        ['label' => 'لیست‌ها', 'url' => route('web.product.custom-lists.index')],
        ['label' => $list['name'] ?? 'جزئیات لیست'],
    ];

    $actionButtons = [
        ['label' => 'بازگشت به لیست‌ها', 'url' => route('web.product.custom-lists.index'), 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'],
    ];

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

    $columns = [
        ['key' => 'title', 'label' => 'عنوان', 'type' => 'text', 'bold' => true],
        ['key' => 'code', 'label' => 'کد', 'type' => 'text', 'monospace' => true],
    ];

    foreach ($activeFields as $field) {
        $columns[] = ['key' => 'field' . $field['num'], 'label' => $field['label'], 'type' => 'text'];
    }

    $columns[] = ['key' => 'is_active', 'label' => 'وضعیت', 'type' => 'status-badge', 'trueLabel' => 'فعال', 'falseLabel' => 'غیرفعال'];
@endphp

<x-panel::layouts.app :title="$list['name'] ?? 'جزئیات لیست'">
    <x-panel::dashboard.sidebar section="product" />

    <main class="flex-1 flex flex-col">
        <x-panel::dashboard.header
            :pageTitle="$list['name'] ?? 'جزئیات لیست'"
            :breadcrumbs="$breadcrumbs"
            :actionButtons="$actionButtons"
        />

        <div class="flex-1 p-6 lg:p-8">

            {{-- هدر لیست --}}
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="{{ $list['icon'] ?? 'fa-solid fa-clipboard-list' }} text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-text-primary leading-snug">{{ $list['name'] }}</h2>
                            <p class="text-sm font-mono text-text-muted leading-normal mt-1">{{ $list['name_en'] ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- ایمپورت --}}
                        <div class="relative" data-dropdown-container>
                            <button onclick="toggleImportDropdown()" class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
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
                                <input type="file" id="importFile" accept=".xlsx,.xls,.csv" class="hidden">
                            </div>
                        </div>

                        {{-- اکسپورت --}}
                        <button class="bg-bg-secondary text-text-secondary border border-border-medium px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-sm leading-normal flex items-center gap-2">
                            <i class="fa-solid fa-file-export"></i>
                            <span>اکسپورت</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- فرم افزودن آیتم (Inline) --}}
            <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                <h3 class="text-base font-semibold text-text-primary leading-snug mb-4">
                    <i class="fa-solid fa-plus-circle text-primary ml-2"></i>
                    افزودن آیتم جدید
                </h3>
                <form data-ajax
                      action="{{ route('api.v1.admin.product.custom-lists.items.store', ['custom_list' => $list['id']]) }}"
                      data-method="POST"
                      data-on-success="reload">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <x-panel::forms.input
                            name="title"
                            label="عنوان"
                            placeholder="عنوان آیتم"
                            :required="true"
                        />

                        <x-panel::forms.input
                            name="code"
                            label="کد"
                            placeholder="کد آیتم"
                        />

                        @foreach ($activeFields as $field)
                            <x-panel::forms.input
                                name="field{{ $field['num'] }}"
                                label="{{ $field['label'] }}"
                                placeholder="{{ $field['label'] }}"
                                type="{{ $field['type'] === 'number' ? 'number' : 'text' }}"
                            />
                        @endforeach

                        <div class="flex items-stretch">
                            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-base leading-normal whitespace-nowrap flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i>
                                <span>افزودن</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- جدول آیتم‌ها --}}
            <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                {{-- جستجو --}}
                <div class="px-6 py-4 border-b border-border-light">
                    <div class="flex items-center gap-4">
                        <div class="flex-1 max-w-md border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                            <div class="flex items-stretch">
                                <label class="bg-bg-label border-l border-border-light px-3 py-2.5 text-sm text-text-secondary flex items-center leading-normal">
                                    <i class="fa-solid fa-search"></i>
                                </label>
                                <input type="text" id="itemSearchInput"
                                       class="flex-1 px-3 py-2.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                                       placeholder="جستجو در آیتم‌ها...">
                            </div>
                        </div>
                        <span class="text-sm text-text-muted leading-normal mr-auto">
                            {{ count($items ?? []) }} آیتم
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
                                @foreach ($activeFields as $field)
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">{{ $field['label'] }}</th>
                                @endforeach
                                <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items ?? [] as $index => $item)
                                <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200" id="item-row-{{ $item['id'] }}">
                                    <td class="px-6 py-4 text-sm text-text-muted leading-normal">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-base text-text-primary font-medium leading-normal">{{ $item['title'] ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-text-secondary font-mono leading-normal">{{ $item['code'] ?? '-' }}</td>
                                    @foreach ($activeFields as $field)
                                        <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $item['field' . $field['num']] ?? '-' }}</td>
                                    @endforeach
                                    <td class="px-6 py-4">
                                        @if ($item['is_active'] ?? false)
                                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                                                <i class="fa-solid fa-circle text-[6px]"></i>
                                                فعال
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                                                <i class="fa-solid fa-circle text-[6px]"></i>
                                                غیرفعال
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openEditItemModal(@js($item))"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200"
                                                    title="ویرایش">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button data-ajax
                                                    data-action="{{ route('api.v1.admin.product.custom-lists.items.destroy', ['custom_list' => $list['id'], 'item' => $item['id']]) }}"
                                                    data-method="DELETE"
                                                    data-confirm="آیا از حذف این آیتم اطمینان دارید؟"
                                                    data-on-success="remove"
                                                    data-target="#item-row-{{ $item['id'] }}"
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                                                    title="حذف">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + count($activeFields) }}" class="px-6 py-12 text-center text-text-muted">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 block opacity-40"></i>
                                        <p class="text-base">هنوز آیتمی اضافه نشده است</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (!empty($pagination))
                    <div class="border-t border-border-light">
                        <x-panel::ui.pagination :pagination="$pagination" />
                    </div>
                @endif
            </div>

        </div>
    </main>

    {{-- Edit Item Modal --}}
    @include('panel::products.modals.edit-item-modal', ['activeFields' => $activeFields, 'list' => $list])

    @push('scripts')
        @vite('Applications/AdminPanel/Resources/js/pages/product-list-items.js')
        <script>
            // ایمپورت/اکسپورت dropdown
            function toggleImportDropdown() {
                document.getElementById('importDropdown').classList.toggle('hidden');
            }
            function downloadTemplate() {
                // TODO: implement template download
                document.getElementById('importDropdown').classList.add('hidden');
            }
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('importDropdown');
                if (!e.target.closest('[data-dropdown-container]') && dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    @endpush
</x-panel::layouts.app>
