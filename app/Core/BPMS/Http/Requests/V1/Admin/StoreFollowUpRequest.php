<?php

declare(strict_types=1);

namespace App\Core\BPMS\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Enums\FollowUpType;
use Illuminate\Validation\Rule;

class StoreFollowUpRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_id'             => ['required', 'integer', Rule::exists(Task::class, 'id')],
            'content'             => ['required', 'string', 'max:5000'],
            'type'                => ['nullable', 'integer', Rule::in(array_column(FollowUpType::cases(), 'value'))],
            'next_follow_up_date' => ['nullable', 'date'],
            'attachment'          => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'task_id.required' => 'شناسه کار الزامی است',
            'task_id.exists'   => 'کار مورد نظر یافت نشد',
            'content.required' => 'متن یادداشت الزامی است',
            'content.max'      => 'متن یادداشت نمی‌تواند بیشتر از ۵۰۰۰ کاراکتر باشد',
            'attachment.max'   => 'حجم فایل پیوست نمی‌تواند بیشتر از ۱۰ مگابایت باشد',
        ];
    }
}
