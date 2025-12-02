<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\Permission;
use App\Core\Organization\Entities\Role;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Role::class, 'name')->ignore($this->route('role')),
            ],
            'title'         => 'nullable|string|max:255',
            'permissions'   => 'nullable|array',
            'permissions.*' => ['string', Rule::exists(Permission::class, 'name')],
        ];
    }
}
