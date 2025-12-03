<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Core\Organization\Entities\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'primary_role'      => ['nullable', 'integer', Rule::exists(Role::class, 'id')],
            'secondary_roles'   => ['array'],
            'secondary_roles.*' => ['integer', 'distinct', Rule::exists(Role::class, 'id')],
        ];
    }
}
