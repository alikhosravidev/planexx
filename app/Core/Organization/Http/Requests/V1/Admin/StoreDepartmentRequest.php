<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;

class StoreDepartmentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id'   => 'nullable|exists:departments,id',
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:departments,code',
            'manager_id'  => 'nullable|exists:users,id',
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
        ];
    }
}
