<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\Organization\Entities\User;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'workflow_id'      => ['required', 'integer', Rule::exists(Workflow::class, 'id')],
            'current_state_id' => ['nullable', 'integer', Rule::exists(WorkflowState::class, 'id')],
            'assignee_id'      => ['required', 'integer', Rule::exists(User::class, 'id')],
            'priority'         => ['required', 'integer', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'due_date'         => ['nullable', 'date', 'after:today'],
            'estimated_hours'  => ['nullable', 'numeric'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'assignee_id' => $this->assignee,
        ]);
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'عنوان کار الزامی است',
            'workflow_id.required' => 'انتخاب فرایند الزامی است',
            'assignee_id.required' => 'انتخاب مسئول الزامی است',
            'priority.required'    => 'اولویت کار الزامی است',
            'due_date.after'       => 'ددلاین باید بعد از امروز باشد',
        ];
    }
}
