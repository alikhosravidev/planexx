<?php

declare(strict_types=1);

namespace App\Core\BPMS\Mappers;

use App\Core\BPMS\DTOs\WorkflowDTO;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Http\Requests\V1\Admin\StoreWorkflowRequest;
use App\Core\BPMS\Http\Requests\V1\Admin\UpdateWorkflowRequest;
use App\Domains\Department\DepartmentId;
use App\Domains\User\UserId;
use App\ValueObjects\Slug;

class WorkflowMapper
{
    public function fromStoreRequest(StoreWorkflowRequest $request): WorkflowDTO
    {
        return new WorkflowDTO(
            name        : $request->string('name')->toString(),
            slug        : $request->filled('slug')
                ? new Slug($request->string('slug')->toString())
                : null,
            description : $request->input('description'),
            departmentId: $request->filled('department_id')
                ? new DepartmentId((int) $request->input('department_id'))
                : null,
            ownerId     : $request->filled('workflow_owner_id')
                ? new UserId((int) $request->input('workflow_owner_id'))
                : null,
            createdBy   : new UserId($request->user()->id),
            isActive    : $request->boolean('is_active', true),
            allowedRoles: $request->input('allowed_roles', []),
            states: $request->input('states', []),
        );
    }

    public function fromUpdateRequest(UpdateWorkflowRequest $request, Workflow $workflow): WorkflowDTO
    {
        return new WorkflowDTO(
            name        : $request->input('name', $workflow->name),
            slug        : $request->filled('slug')
                ? new Slug($request->string('slug')->toString())
                : ($workflow->slug ? new Slug($workflow->slug) : null),
            description : $request->input('description', $workflow->description),
            departmentId: $request->filled('department_id')
                ? new DepartmentId((int) $request->input('department_id'))
                : ($workflow->department_id ? new DepartmentId($workflow->department_id) : null),
            ownerId     : $request->filled('workflow_owner_id')
                ? new UserId((int) $request->input('workflow_owner_id'))
                : ($workflow->owner_id ? new UserId($workflow->owner_id) : null),
            createdBy   : $workflow->created_by
                ? new UserId($workflow->created_by)
                : null,
            isActive    : $request->boolean('is_active', (bool) $workflow->is_active),
            allowedRoles: $request->input('allowed_roles') ?? $workflow->allowedRoles()->pluck('id')->toArray(),
            states: $request->input('states')              ?? $workflow->states()->pluck('id')->toArray(),
        );
    }
}
