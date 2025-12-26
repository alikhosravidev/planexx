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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-panel::forms.input
                class="min-w-[100px]"
                name="name"
                :value="$workflow['name'] ?? ''"
                label="نام فرایند"
                placeholder="نام فرایند را وارد کنید"
                required
            />

            <x-panel::forms.input
                class="min-w-[100px]"
                name="slug"
                :value="$workflow['slug'] ?? ''"
                label="Slug"
                direction="ltr"
            />
        </div>

        <x-panel::forms.textarea
            class="min-w-[100px]"
            name="description"
            :value="$workflow['description']['full'] ?? ''"
            label="توضیحات"
            placeholder="توضیحات فرایند را وارد کنید"
            rows="2"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-panel::forms.tom-select-ajax
                name="department_id"
                label="دپارتمان"
                :value="$workflow['department_id'] ?? null"
                template="departments"
                class="min-w-[100px]"
                :preload="true"
                :url="route('api.v1.admin.org.departments.index', ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => ''], 'includes' => 'children'])"/>

            <x-panel::forms.tom-select-ajax
                name="workflow_owner_id"
                label="مالک فرایند"
                :value="$workflow['owner_id'] ?? null"
                class="min-w-[100px]"
                :preload="true"
                :url="route('api.v1.admin.org.users.keyValList', ['field' => 'full_name', 'filter' => ['user_type' => 2]])"/>
        </div>

        <x-panel::forms.checkbox-badges
            name="allowed_roles"
            label="نقش‌های مجاز برای دسترسی"
            :options="$roles ?? []"
            :selected="$selectedRoleIds ?? []"
            class="mt-2"
        />
    </div>
</div>
