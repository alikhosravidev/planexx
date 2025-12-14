@props([
    'folders' => [],
    'currentFolder' => null
])

@php
    $folderOptions = [];

    foreach ($folders as $folder) {
        $folderOptions[$folder['id']] = $folder['name'];
    }
@endphp

<div
    id="uploadModal"
    data-modal
    data-modal-backdrop
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">


    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[600px] w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
            <h3 class="text-xl font-semibold text-text-primary leading-snug">آپلود فایل {!! null !== $currentFolder ? "در پوشه <span class='text-orange-500'>{$currentFolder['name']}</span>" : 'جدید' !!}</h3>
            <button type="button" data-modal-close class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded-lg transition-all duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

            <form
                data-ajax
                action="{{ route('api.v1.admin.file-manager.files.store') }}"
                method="POST"
                enctype="multipart/form-data"
                data-on-success="reload"
                data-loading-class="opacity-50 pointer-events-none"
                class="p-6">
                @csrf

                @if(null !== $currentFolder)
                    <input type="hidden" name="folder_id" value="{{ $currentFolder['id'] }}">
                @endif

                <label
                    data-drop-zone
                    class="border-2 border-dashed border-border-medium rounded-2xl p-10 text-center mb-6 hover:border-primary hover:bg-primary/5 transition-all duration-200 cursor-pointer block">
                    <div class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-primary"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-text-primary mb-2">فایل‌ها را اینجا رها کنید</h4>
                    <p class="text-sm text-text-muted mb-4">یا کلیک کنید تا فایل انتخاب شود</p>
                    <input type="file" name="file" class="hidden" required data-file-input>
                    <span class="bg-bg-secondary text-text-secondary px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-border-light transition-all duration-200 inline-block">
                        انتخاب فایل
                    </span>
                </label>

                <div class="mb-4">
                    <x-panel::forms.input
                        name="title"
                        label="عنوان فایل"
                        placeholder="عنوان توصیفی فایل را وارد کنید"
                        class="min-w-[120px]"
                    />
                </div>

                @if(! empty($folderOptions))
                    <div class="mb-4">
                        <x-panel::forms.select
                            name="folder_id"
                            label="پوشه مقصد"
                            :options="$folderOptions"
                            placeholder="ریشه اسناد (بدون پوشه)"
                            class="min-w-[120px]"
                        />
                    </div>
                @endif

                <div class="mb-6">
                    <label class="flex items-center gap-3 p-4 border border-border-medium rounded-xl cursor-pointer hover:border-primary transition-all duration-200">
                        <input type="checkbox" name="is_temporary" value="1" class="w-5 h-5 accent-primary">
                        <div>
                            <span class="text-sm font-medium text-text-primary">فایل موقت</span>
                            <p class="text-xs text-text-muted mt-0.5">این فایل به صورت موقت ذخیره می‌شود و قابل پاکسازی است</p>
                        </div>
                        <i class="fa-solid fa-hourglass-half text-amber-500 mr-auto"></i>
                    </label>
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
                    <i class="fa-solid fa-cloud-arrow-up" data-icon></i>
                    <i class="fa-solid fa-spinner fa-spin hidden" data-loading-icon></i>
                    <span data-text>آپلود</span>
                    <span class="hidden" data-loading-text>در حال آپلود...</span>
                </button>
            </div>
        </form>
    </div>
</div>
