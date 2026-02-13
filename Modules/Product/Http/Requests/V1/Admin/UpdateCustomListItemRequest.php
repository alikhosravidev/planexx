<?php

declare(strict_types=1);

namespace Modules\Product\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use Modules\Product\Entities\CustomListAttribute;

class UpdateCustomListItemRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_code'         => ['nullable', 'string', 'max:100'],
            'values'                 => ['nullable', 'array'],
            'values.*.attribute_id'  => ['required_with:values', 'integer', 'exists:' . CustomListAttribute::TABLE . ',id'],
            'values.*.value_text'    => ['nullable', 'string'],
            'values.*.value_number'  => ['nullable', 'numeric'],
            'values.*.value_date'    => ['nullable', 'date'],
            'values.*.value_boolean' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'values.*.attribute_id.required_with' => trans('Product::validation.custom_list_item.attribute_id_required'),
            'values.*.attribute_id.exists'        => trans('Product::validation.custom_list_item.attribute_id_exists'),
            'values.*.value_number.numeric'       => trans('Product::validation.custom_list_item.value_number_numeric'),
            'values.*.value_date.date'            => trans('Product::validation.custom_list_item.value_date_date'),
        ];
    }
}
