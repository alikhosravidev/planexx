@php
    $userTypeLower = strtolower($userType);
    $titlePrefix = isset($user) ? 'ویرایش' : 'افزودن';
    $typeLabels = [
        'Employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'editTitle' => $titlePrefix . ' کارمند'],
        'Customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'editTitle' => $titlePrefix . ' مشتری'],
        'User' => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'editTitle' => $titlePrefix . ' کاربر'],
    ];

    $pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['editTitle'] : $titlePrefix . ' کاربر';
    $listTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['title'] : 'مدیریت کاربران';
    $listUrl = route('web.org.users.index', ['type' => strtolower($userType)]);
    $currentPage = "org-{$userTypeLower}";

    $breadcrumbs = [
        ['label' => 'خانه', 'url' => route('web.dashboard')],
        ['label' => 'ساختار سازمانی', 'url' => route('web.org.dashboard')],
        ['label' => $listTitle, 'url' => $listUrl],
        ['label' => $pageTitle],
    ];

    $actionButtons = [];
    if (isset($user)) {
        $actionButtons[] = ['label' => 'مشاهده جزئیات', 'url' => route('web.org.users.show', ['user' => $user['id'] ?? null]), 'icon' => 'fa-solid fa-eye', 'type' => 'outline'];
    }
    $actionButtons[] = ['label' => 'بازگشت', 'url' => $listUrl, 'icon' => 'fa-solid fa-arrow-right', 'type' => 'outline'];

    $isActive = !empty($user['is_active']);
@endphp

<x-panel::layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-panel::dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col min-w-0">
            <x-panel::dashboard.header
                :title="$pageTitle"
                :breadcrumbs="$breadcrumbs"
                :actions="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                <form data-ajax
                      data-method="{{ isset($user) ? 'PUT' : 'POST' }}"
                      action="{{ isset($user) ? route('api.v1.admin.org.users.update', ['user' => $user['id'] ?? null]) : route('api.v1.admin.org.users.store') }}"
                      data-on-success="redirect"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات پایه</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input class="min-w-[140px]" name="full_name" :value="$user['full_name'] ?? ''" label="نام کامل" required/>
                            <x-panel::forms.input class="min-w-[140px]" name="first_name" :value="$user['first_name'] ?? ''" label="نام"/>
                            <x-panel::forms.input class="min-w-[140px]" name="last_name" :value="$user['last_name'] ?? ''" label="نام خانوادگی"/>
                            <x-panel::forms.input class="min-w-[140px] text-left" name="mobile" type="tel" :value="$user['mobile'] ?? ''" label="شماره موبایل" required/>
                            <x-panel::forms.input class="min-w-[140px] text-left" name="email" type="email" :value="$user['email'] ?? ''" label="ایمیل"/>
                            <x-panel::forms.input class="min-w-[140px] text-left" name="national_code" maxlength="10" :value="$user['national_code'] ?? ''" label="کد ملی"/>
                            <x-panel::forms.tom-select-ajax
                                name="gender"
                                label="جنسیت"
                                :value="$user['gender']['value'] ?? null"
                                class="min-w-[140px]"
                                :url="route('api.v1.admin.enums.keyValList', ['enum' => 'GenderEnum'])"/>
                            <x-panel::forms.date name="birth_date" class="min-w-[140px]" :value="$user['birth_date']['main'] ?? null" label="تاریخ تولد"/>
                        </div>
                    </div>

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات کاربری</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.tom-select-ajax
                                name="user_type"
                                label="نوع کاربر"
                                required
                                :preload="true"
                                :value="$userTypeValue"
                                class="min-w-[140px]"
                                :url="route('api.v1.admin.enums.keyValList', ['enum' => 'UserTypeEnum'])"/>

                            <x-panel::forms.input class="min-w-[140px] text-left" name="password" type="password" label="رمز عبور جدید"
                                           placeholder="خالی بگذارید اگر تغییری نمی‌خواهید"/>

                            <div class="lg:col-span-2">
                                <x-panel::forms.radio name="is_active" label="وضعیت کاربر" :value="$isActive ? '1' : '0'"
                                               :options="['1' => 'فعال', '0' => 'غیرفعال']"/>
                            </div>
                        </div>
                    </div>

                    {{--TODO: store employee data--}}
                    <div
                        class="bg-bg-primary border border-border-light rounded-2xl p-6 js-employee-section {{ $userType === 'Employee' ? '' : 'hidden' }}"
                    >
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات استخدامی</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-panel::forms.input class="min-w-[140px] text-left" name="employee_code" :value="$user['employee_code'] ?? ''" label="کد پرسنلی"/>

                            @php
                                $jobPositionId = $user['job_position']['id'] ?? null;
                                $jobPositionName = $user['job_position']['name'] ?? null;
                                $managerId = $user['direct_manager']['id'] ?? null;
                                $managerName = $user['direct_manager']['full_name'] ?? null;
                                $primaryDepartment = collect($user['departments'] ?? [])->firstWhere('pivot.is_primary', true);
                                $departmentId = $primaryDepartment['id'] ?? null;
                                $departmentName = $primaryDepartment['name'] ?? null;
                            @endphp

                            <x-panel::forms.tom-select-ajax
                                name="direct_manager_id"
                                label="مدیر مستقیم"
                                :value="$managerId"
                                class="min-w-[140px]"
                                :url="route('api.v1.admin.org.users.keyValList', ['field' => 'full_name', 'filter' => ['user_type' => 2]])"/>

                            <x-panel::forms.tom-select-ajax
                                name="department_id"
                                label="دپارتمان اصلی"
                                :value="$departmentId"
                                template="departments"
                                class="min-w-[140px]"
                                :url="route('api.v1.admin.org.departments.index', ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => ''], 'includes' => 'children'])"/>

                            <x-panel::forms.date
                                name="employment_date"
                                :value="$user['employment_date']['main'] ?? null"
                                class="min-w-[140px]"
                                label="تاریخ استخدام"/>
                        </div>
                    </div>

                    {{--TODO: store user avatar--}}
                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <x-panel::ui.profile-image-upload :value="$user['avatar']['file_url'] ?? null" standalone/>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-panel::ui.button type="submit" variant="green" icon="fa-solid fa-check">ذخیره تغییرات</x-panel::ui.button>
                        <a href="{{ $listUrl }}"
                           class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            <i class="fa-solid fa-times ml-2"></i>
                            <span>انصراف</span>
                        </a>
                        @isset($user)
                            <x-panel::ui.button
                                data-ajax
                                data-method="DELETE"
                                data-confirm="آیا از حذف این کاربر اطمینان دارید؟"
                                data-action="{{ route('api.v1.admin.org.users.destroy', $user['id']) }}"
                                data-on-success="redirect"
                                data-redirect-url="{{ route('web.org.users.index') }}"
                                type="button"
                                icon="fa-solid fa-trash"
                                variant="danger"
                                class="mr-auto">حذف کاربر</x-panel::ui.button>
                        @endisset
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var userTypeSelect = document.querySelector('select[name="user_type"]');
            var employeeSection = document.querySelector('.js-employee-section');

            if (!userTypeSelect || !employeeSection) {
                return;
            }

            var EMPLOYEE_VALUE = @json(\App\Core\Organization\Enums\UserTypeEnum::Employee->value);

            function toggleEmployeeSection() {
                var currentValue = userTypeSelect.value;

                if (String(currentValue) === String(EMPLOYEE_VALUE)) {
                    employeeSection.classList.remove('hidden');
                } else {
                    employeeSection.classList.add('hidden');
                }
            }

            userTypeSelect.addEventListener('change', toggleEmployeeSection);

            toggleEmployeeSection();
        });
    </script>
</x-panel::layouts.app>
