@props(['folders' => [], 'folder' => null])

@php
    $isEdit = isset($folder) && !empty($folder) && isset($folder['id']);
    $modalTitle = $isEdit ? 'ویرایش پوشه' : 'ایجاد پوشه جدید';
    $submitUrl = $isEdit 
        ? route('api.v1.admin.file-manager.folders.update', ['folder' => $folder['id']]) 
        : route('api.v1.admin.file-manager.folders.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<div
    id="folderModal"
    data-modal
    data-modal-backdrop
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[500px] w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-text-primary leading-snug">{{ $modalTitle }}</h3>
                <p class="text-sm text-text-muted mt-1">در <span class="text-primary font-medium" data-folder-parent-name>ریشه اسناد</span></p>
            </div>
            <button type="button" data-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

            <form
                data-ajax
                action="{{ $submitUrl }}"
                data-method="{{ $method }}"
                data-on-success="reload"
                data-loading-class="opacity-50 pointer-events-none"
                class="p-6">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-text-secondary mb-2">نام پوشه</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ $folder['name'] ?? '' }}"
                        required
                        data-folder-name-input
                        class="w-full px-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary outline-none focus:border-primary focus:shadow-focus transition-all duration-200"
                        placeholder="نام پوشه را وارد کنید...">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-text-secondary mb-2">پوشه والد</label>
                    <select
                        name="parent_id"
                        data-folder-parent-select
                        class="w-full px-4 py-3 border border-border-medium rounded-xl text-sm text-text-primary outline-none focus:border-primary focus:shadow-focus transition-all duration-200 cursor-pointer bg-white">
                        <option value="">ریشه اسناد (بدون والد)</option>
                        @foreach ($folders as $folderOption)
                            @if(isset($folderOption['id']) && isset($folderOption['name']))
                                @if (!$isEdit || $folderOption['id'] !== ($folder['id'] ?? null))
                                    <option value="{{ $folderOption['id'] }}" {{ isset($folder['parent_id']) && $folder['parent_id'] == $folderOption['id'] ? 'selected' : '' }}>
                                        {{ $folderOption['name'] }}
                                    </option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-text-secondary mb-3">رنگ پوشه</label>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $colors = ['purple', 'pink', 'green', 'blue', 'amber', 'slate'];
                            $selectedColor = $folder['color'] ?? 'purple';
                        @endphp
                        @foreach ($colors as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}" class="hidden peer" {{ $selectedColor === $color ? 'checked' : '' }}>
                                <div class="w-10 h-10 rounded-xl bg-{{ $color }}-500 flex items-center justify-center peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $color }}-500 transition-all">
                                    <i class="fa-solid fa-check text-white opacity-0 peer-checked:opacity-100"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="p-4 bg-bg-secondary rounded-xl mb-6">
                    <p class="text-xs text-text-muted mb-3">پیش‌نمایش پوشه:</p>
                    <div class="flex items-center gap-3">
                        <div class="w-14" data-folder-preview>
                            <svg viewBox="0 0 1024 1024" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                                <path d="M853.333333 256H469.333333l-85.333333-85.333333H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v170.666667h853.333334v-85.333334c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="#A78BFA"></path>
                                <path d="M853.333333 256H170.666667c-46.933333 0-85.333333 38.4-85.333334 85.333333v426.666667c0 46.933333 38.4 85.333333 85.333334 85.333333h682.666666c46.933333 0 85.333333-38.4 85.333334-85.333333V341.333333c0-46.933333-38.4-85.333333-85.333334-85.333333z" fill="#7C3AED"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text-primary" data-folder-preview-name>{{ $folder['name'] ?? 'نام پوشه' }}</p>
                            <p class="text-xs text-text-muted">{{ $folder['files_count'] ?? '0' }} فایل</p>
                        </div>
                    </div>
                </div>

            <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3 -mx-6 -mb-6">
                <button
                    type="button"
                    data-modal-close
                    class="px-5 py-2.5 border border-border-medium text-text-secondary rounded-lg font-medium hover:bg-bg-secondary transition-all duration-200 text-sm">
                    انصراف
                </button>
                <button
                    type="submit"
                    data-submit-button
                    class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-folder-plus" data-icon></i>
                    <i class="fa-solid fa-spinner fa-spin hidden" data-loading-icon></i>
                    <span>{{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد پوشه' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
