<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Entities\User;
use Illuminate\Validation\Rule;

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
            'gender'            => ['nullable', Rule::in([1,2,3])],
            'birth_date'        => ['nullable', 'date'],
            'user_type'         => ['required', Rule::in(['User','Customer','Employee'])],
            'is_active'         => ['required', 'boolean'],
            'password'          => ['nullable', 'string', 'min:6'],
            'job_position_id'   => ['nullable', Rule::exists(JobPosition::class, 'id')],
            'direct_manager_id' => ['nullable', Rule::exists(User::class, 'id')],
            'department_id'     => ['nullable', Rule::exists(Department::class, 'id')],
            'employment_date'   => ['nullable', 'date'],
            'image_url'         => ['nullable', 'string', 'max:255'],
        ];
    }
}
