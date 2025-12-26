<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Requests\API\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\TaskAction;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\Organization\Entities\User;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = TaskAction::fromString($this->input('action', 'edit'));

        $baseRules = [
            'action' => ['sometimes', 'string', Rule::in(array_column(TaskAction::cases(), 'value'))],
        ];

        if ($action->isEdit()) {
            return array_merge($baseRules, [
                'title'            => ['sometimes', 'required', 'string', 'max:255'],
                'description'      => ['nullable', 'string'],
                'workflow_id'      => ['sometimes', 'required', 'integer', Rule::exists(Workflow::class, 'id')],
                'current_state_id' => ['nullable', 'integer', Rule::exists(WorkflowState::class, 'id')],
                'assignee_id'      => ['sometimes', 'required', 'integer', Rule::exists(User::class, 'id')],
                'priority'         => ['sometimes', 'required', 'integer', Rule::in(array_column(TaskPriority::cases(), 'value'))],
                'due_date'         => ['nullable', 'date'],
                'estimated_hours'  => ['nullable', 'numeric'],
            ]);
        }

        if ($action->isAddNote()) {
            return array_merge($baseRules, [
                'content'             => ['required', 'string', 'max:5000'],
                'next_follow_up_date' => ['nullable', 'date'],
                'attachment'          => ['nullable', 'file', 'max:10240'],
            ]);
        }

        if ($action->isForward()) {
            return array_merge($baseRules, [
                'assignee_id'   => ['required', 'integer', Rule::exists(User::class, 'id')],
                'description'   => ['nullable', 'string', 'max:5000'],
                'next_state_id' => ['nullable', 'integer', Rule::exists(WorkflowState::class, 'id')],
            ]);
        }

        return $baseRules;
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
            'content.required'     => 'متن یادداشت الزامی است',
            'content.max'          => 'متن یادداشت نمی‌تواند بیشتر از ۵۰۰۰ کاراکتر باشد',
            'description.max'      => 'توضیحات نمی‌تواند بیشتر از ۵۰۰۰ کاراکتر باشد',
            'attachment.max'       => 'حجم فایل پیوست نمی‌تواند بیشتر از ۱۰ مگابایت باشد',
        ];
    }
}
