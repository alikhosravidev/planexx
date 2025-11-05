<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Enums\TierEnum;
use Illuminate\Validation\Rule;

class StoreJobPositionRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'code'        => 'nullable|string|max:50|unique:job_positions,code',
            'tier'        => ['nullable', 'integer', Rule::enum(TierEnum::class)],
            'image_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
        ];
    }
}
