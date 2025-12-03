<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Entities\Tag;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:100', Rule::unique(Tag::class, 'name')->ignore($this->route('tag'))->withoutTrashed()],
            'slug'        => ['sometimes', 'nullable', 'string', 'max:100', Rule::unique(Tag::class, 'slug')->ignore($this->route('tag'))->withoutTrashed()],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'color'       => ['sometimes', 'nullable', 'string'],
            'icon'        => ['sometimes', 'nullable', 'string', 'max:50'],
        ];
    }
}
