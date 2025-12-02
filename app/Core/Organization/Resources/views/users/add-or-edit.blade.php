@php
    $userType = $user['user_type']['name'] ?? 'User';
    $userTypeLower = strtolower($userType);
    $typeLabels = [
        'Employee' => ['title' => 'کارکنان', 'singular' => 'کارمند', 'editTitle' => 'ویرایش کارمند'],
        'Customer' => ['title' => 'مشتریان', 'singular' => 'مشتری', 'editTitle' => 'ویرایش مشتری'],
        'User' => ['title' => 'کاربران عادی', 'singular' => 'کاربر', 'editTitle' => 'ویرایش کاربر'],
    ];

    $pageTitle = isset($typeLabels[$userType]) ? $typeLabels[$userType]['editTitle'] : 'ویرایش کاربر';
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

    $genderValue = $user['gender']['value'] ?? null; // 1 => male, 2 => female (assumed)
    $isActive = !empty($user['is_active']);
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
            name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col">
            <x-dashboard.header
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
                            <x-forms.input class="min-w-[140px]" name="full_name" :value="$user['full_name'] ?? ''" label="نام کامل" required />
                            <x-forms.input class="min-w-[140px]" name="first_name" :value="$user['first_name'] ?? ''" label="نام" />
                            <x-forms.input class="min-w-[140px]" name="last_name" :value="$user['last_name'] ?? ''" label="نام خانوادگی" />
                            <x-forms.input class="min-w-[140px]" name="mobile" type="tel" :value="$user['mobile'] ?? ''" label="شماره موبایل" required />
                            <x-forms.input class="min-w-[140px]" name="email" type="email" :value="$user['email'] ?? ''" label="ایمیل" />
                            <x-forms.input class="min-w-[140px]" name="national_code" maxlength="10" :value="$user['national_code'] ?? ''" label="کد ملی" />
                            <x-forms.select class="min-w-[140px]" name="gender" label="جنسیت" :value="$genderValue" class="min-w-[140px]"
                                :options="[1 => 'مرد', 2 => 'زن']" />
                            <x-forms.date name="birth_date" :value="$user['birth_date'] ?? null" label="تاریخ تولد" />
                        </div>
                    </div>

                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات کاربری</h2>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @php
                                $userTypeValue = $user['user_type']['name'] ?? ucfirst(request('user_type')) ?? 'User';
                            @endphp
                            <x-forms.select name="user_type" label="نوع کاربر" required :value="$userTypeValue" class="min-w-[140px]"
                                :options="['User' => 'کاربر عادی', 'Customer' => 'مشتری', 'Employee' => 'کارمند']"/>

                            <x-forms.input class="min-w-[140px]" name="password" type="password" label="رمز عبور جدید" placeholder="خالی بگذارید اگر تغییری نمی‌خواهید" />

                            <div class="lg:col-span-2">
                                <x-forms.radio name="is_active" label="وضعیت کاربر" :value="$isActive ? '1' : '0'"
                                               :options="['1' => 'فعال', '0' => 'غیرفعال']"/>
                            </div>
                        </div>
                    </div>

                    {{--TODO: store employee data--}}
                    @if($userType === 'Employee')
                        <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                            <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">اطلاعات استخدامی</h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <x-forms.input class="min-w-[140px]" name="employee_code" :value="$user['employee_code'] ?? ''" label="کد پرسنلی" />

                                @php
                                    $jobPositionId = $user['job_position']['id'] ?? null;
                                    $jobPositionName = $user['job_position']['name'] ?? null;
                                    $managerId = $user['direct_manager']['id'] ?? null;
                                    $managerName = $user['direct_manager']['full_name'] ?? null;
                                    $primaryDepartment = collect($user['departments'] ?? [])->firstWhere('pivot.is_primary', true);
                                    $departmentId = $primaryDepartment['id'] ?? null;
                                    $departmentName = $primaryDepartment['name'] ?? null;
                                @endphp

                                <x-forms.select name="job_position_id" label="موقعیت شغلی" :value="$jobPositionId" class="min-w-[140px]"
                                                :options="$jobPositionId ? [$jobPositionId => $jobPositionName] : []" />

                                <x-forms.select name="direct_manager_id" label="مدیر مستقیم" :value="$managerId" class="min-w-[140px]"
                                                :options="$managerId ? [$managerId => $managerName] : []" />

                                <x-forms.select name="department_id" label="دپارتمان اصلی" :value="$departmentId" class="min-w-[140px]"
                                                :options="$departmentId ? [$departmentId => $departmentName] : []" />

                                <x-forms.date name="employment_date" :value="$user['employment_date'] ?? null" label="تاریخ استخدام" />
                            </div>
                        </div>
                    @endif

                    {{--TODO: store user avatar--}}
                    <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                        <x-ui.profile-image-upload :value="$user['image_url'] ?? null" standalone/>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-ui.button type="submit" variant="green" icon="fa-solid fa-check">ذخیره تغییرات</x-ui.button>
                        <a href="{{ $listUrl }}" class="inline-flex items-center gap-2 bg-bg-secondary text-text-secondary border border-border-medium px-xl py-md rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
                            <i class="fa-solid fa-times ml-2"></i>
                            <span>انصراف</span>
                        </a>
                        <x-ui.button type="button" icon="fa-solid fa-trash" variant="danger" class="mr-auto">حذف کاربر</x-ui.button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-layouts.app>
