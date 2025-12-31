<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Rules\ValidWorkflowStates;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\Role;
use App\Core\Organization\Entities\User;
use Illuminate\Validation\Rule;

class UpdateWorkflowRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Workflow|null $workflow */
        $workflowId = $this->route('workflow');

        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:150',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique(Workflow::class, 'slug')->ignore($workflowId)->withoutTrashed(),
            ],
            'description'       => ['sometimes', 'nullable', 'string'],
            'department_id'     => ['sometimes', 'nullable', Rule::exists(Department::class, 'id')],
            'workflow_owner_id' => ['sometimes', 'nullable', Rule::exists(User::class, 'id')],
            'is_active'         => ['sometimes', 'boolean'],
            'allowed_roles'     => ['sometimes', 'array'],
            'allowed_roles.*'   => ['integer', Rule::exists(Role::class, 'id')],

            'states'                   => ['required', 'array', 'min:1', new ValidWorkflowStates()],
            'states.*.id'              => ['nullable', 'integer'],
            'states.*.name'            => ['required_with:states', 'string', 'max:150'],
            'states.*.slug'            => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/'],
            'states.*.description'     => ['nullable', 'string'],
            'states.*.color'           => ['nullable', 'string', 'max:20'],
            'states.*.order'           => ['nullable', 'integer', 'min:0'],
            'states.*.position'        => ['required_with:states', 'in:start,middle,final-success,final-failed,final-closed'],
            'states.*.allowed_roles'   => ['nullable', 'array'],
            'states.*.allowed_roles.*' => ['numeric', Rule::exists(Role::class, 'id')],
        ];
    }
}
