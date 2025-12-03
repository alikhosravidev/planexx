<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Entities\User;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'         => ['sometimes', 'string', 'max:100'],
            'first_name'        => ['sometimes', 'nullable', 'string', 'max:60'],
            'last_name'         => ['sometimes', 'nullable', 'string', 'max:60'],
            'mobile'            => ['required', 'string', 'mobile', Rule::unique(User::class, 'mobile')->ignore($this->route('user'))],
            'email'             => ['nullable', 'email', Rule::unique(User::class, 'email')->ignore($this->route('user'))],
            'national_code'     => ['nullable', 'digits:10', Rule::unique(User::class, 'national_code')->ignore($this->route('user'))],
            'gender'            => ['sometimes', 'nullable', Rule::in([1,2,3])],
            'birth_date'        => ['sometimes', 'nullable', 'date'],
            'user_type'         => ['sometimes', Rule::in(['User','Customer','Employee'])],
            'is_active'         => ['sometimes', 'boolean'],
            'password'          => ['sometimes', 'nullable', 'string', 'min:6'],
            'job_position_id'   => ['sometimes', 'nullable', Rule::exists(JobPosition::class, 'id')],
            'direct_manager_id' => ['sometimes', 'nullable', Rule::exists(User::class, 'id')],
            'department_id'     => ['sometimes', 'nullable', Rule::exists(Department::class, 'id')],
            'employment_date'   => ['sometimes', 'nullable', 'date'],
            'employee_code'     => ['sometimes', 'nullable', 'string', 'max:50'],
            'image_url'         => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
