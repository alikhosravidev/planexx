<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Entities\Tag;
use Illuminate\Validation\Rule;

class StoreTagRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', Rule::unique(Tag::class, 'name')->withoutTrashed()],
            'slug'        => ['nullable', 'string', 'max:100', Rule::unique(Tag::class, 'slug')->withoutTrashed()],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['nullable', 'string'],
            'icon'        => ['nullable', 'string', 'max:50'],
        ];
    }
}
