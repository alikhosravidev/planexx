<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Enums\DepartmentTypeEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreDepartmentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id'   => ['nullable', Rule::exists(Department::class, 'id')],
            'name'        => 'required|string|max:255',
            'code'        => ['nullable', 'string', 'max:50', Rule::unique(Department::class, 'code')],
            'manager_id'  => ['nullable', Rule::exists(User::class, 'id')],
            'image_url'   => 'nullable|url',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'type'        => ['required', new Enum(DepartmentTypeEnum::class)],
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'required|boolean',
        ];
    }
}
