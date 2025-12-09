<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Enums\GenderEnum;
use App\Core\Organization\Enums\UserTypeEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'         => ['required', 'string', 'max:100'],
            'first_name'        => ['nullable', 'string', 'max:60'],
            'last_name'         => ['nullable', 'string', 'max:60'],
            'mobile'            => ['required', 'string', 'mobile', Rule::unique(User::class, 'mobile')->withoutTrashed()],
            'email'             => ['nullable', 'email', 'email', Rule::unique(User::class, 'email')->withoutTrashed()],
            'national_code'     => ['nullable', 'digits:10', Rule::unique(User::class, 'national_code')->withoutTrashed()],
            'gender'            => ['nullable', new Enum(GenderEnum::class)],
            'birth_date'        => ['nullable', 'date'],
            'user_type'         => ['required', new Enum(UserTypeEnum::class)],
            'is_active'         => ['required', 'boolean'],
            'password'          => ['nullable', 'string', 'min:6'],
            'job_position_id'   => ['nullable', Rule::exists(JobPosition::class, 'id')],
            'direct_manager_id' => ['nullable', Rule::exists(User::class, 'id')],
            'department_id'     => ['nullable', Rule::exists(Department::class, 'id')],
            'employment_date'   => ['nullable', 'date'],
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'فایل نباید بزرگتر از 2 مگابایت باشد.',
        ];
    }
}
