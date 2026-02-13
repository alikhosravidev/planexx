<?php

declare(strict_types=1);

namespace Modules\Product\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\CustomList;
use Modules\Product\Enums\AttributeDataTypeEnum;

class StoreCustomListRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug'  => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique(CustomList::class, 'slug'),
            ],
            'description' => ['nullable', 'string'],
            'icon_class'  => ['nullable', 'string', 'max:100'],
            'color'       => ['nullable', 'string', 'max:30'],
            'is_active'   => ['nullable', 'boolean'],

            'attributes'               => ['nullable', 'array'],
            'attributes.*.label'       => ['required_with:attributes', 'string', 'max:100'],
            'attributes.*.key_name'    => ['required_with:attributes', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/'],
            'attributes.*.data_type'   => ['nullable', 'integer', Rule::enum(AttributeDataTypeEnum::class)],
            'attributes.*.is_required' => ['nullable', 'boolean'],
            'attributes.*.sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                      => trans('Product::validation.custom_list.title_required'),
            'title.max'                           => trans('Product::validation.custom_list.title_max'),
            'slug.regex'                          => trans('Product::validation.custom_list.slug_regex'),
            'slug.unique'                         => trans('Product::validation.custom_list.slug_unique'),
            'attributes.*.label.required_with'    => trans('Product::validation.custom_list.attribute_label_required'),
            'attributes.*.key_name.required_with' => trans('Product::validation.custom_list.attribute_key_required'),
            'attributes.*.key_name.regex'         => trans('Product::validation.custom_list.attribute_key_regex'),
        ];
    }
}
