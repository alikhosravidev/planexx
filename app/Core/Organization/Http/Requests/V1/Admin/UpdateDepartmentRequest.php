<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Enums\DepartmentTypeEnum;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', Rule::exists(Department::class, 'id')],
            'name'      => 'required|string|max:255',
            'code'      => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(Department::class, 'code')->ignore($this->route('department')),
            ],
            'manager_id'  => ['nullable', Rule::exists(User::class, 'id')],
            'image_url'   => 'nullable|url',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'type'        => ['required', Rule::in(array_column(DepartmentTypeEnum::cases(), 'value'))],
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'required|boolean',
        ];
    }
}
