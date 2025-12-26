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

            <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                    <label class="bg-bg-label border-l border-border-light min-w-[100px] px-3 py-3 text-sm text-text-secondary flex items-center leading-normal">
                        رنگ
                    </label>
                    <div class="flex-1 flex items-center gap-1.5 px-3 py-2 bg-white overflow-x-auto color-palette-container">
                        <input type="hidden" name="states[][color]" value="#E3F2FD" class="state-color-input">
                    </div>
                </div>
            </div>

            <x-panel::forms.select
                name="states[][position]"
                label="موقعیت"
                class="min-w-[100px]"
                select-class="bg-white state-position-select"
                value="middle"
                :options="['start' => 'نقطه شروع', 'middle' => 'میانی', 'final-success' => 'پایان موفق', 'final-failed' => 'پایان ناموفق', 'final-closed' => 'بسته شده']"/>

            {{--<x-panel::forms.tom-select-ajax
                name="states[][default_assignee_id]"
                label="مسئول پیشفرض"
                class="min-w-[120px] md:col-span-2"
                select-class="bg-white"
                wrapper-class="md:col-span-2"
                :url="route('api.v1.admin.org.users.keyValList', ['field' => 'full_name', 'filter' => ['user_type' => 2]])"/>--}}
            <x-panel::forms.select
                name="states[][default_assignee_id]"
                label="مسئول پیشفرض"
                class="min-w-[120px]"
                select-class="bg-white"
                value="middle"
                wrapper-class="md:col-span-2"
                :options="$users ?? []"/>

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

        <input type="hidden" name="states[][id]" class="state-id-input" value="">
        <input type="hidden" name="states[][order]" class="state-order-input" value="1">
    </div>
</template>
