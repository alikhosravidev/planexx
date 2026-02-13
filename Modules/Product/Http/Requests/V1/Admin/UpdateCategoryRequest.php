<?php

declare(strict_types=1);

namespace Modules\Product\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Category;

class UpdateCategoryRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:150',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique(Category::class, 'slug')->ignore($categoryId)->withoutTrashed(),
            ],
            'parent_id'  => ['nullable', 'integer', Rule::exists(Category::class, 'id')],
            'icon_class' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'         => trans('Product::validation.category.name_max'),
            'slug.regex'       => trans('Product::validation.category.slug_regex'),
            'slug.unique'      => trans('Product::validation.category.slug_unique'),
            'parent_id.exists' => trans('Product::validation.category.parent_id_exists'),
        ];
    }
}
