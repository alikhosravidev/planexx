<?php

declare(strict_types=1);

namespace Modules\Product\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductStatusEnum;

class StoreProductRequest extends BaseRequest
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
                Rule::unique(Product::class, 'slug')->withoutTrashed(),
            ],
            'price'          => ['required', 'numeric', 'min:0'],
            'sale_price'     => ['nullable', 'numeric', 'min:0'],
            'status'         => ['nullable', 'numeric', Rule::enum(ProductStatusEnum::class)],
            'is_featured'    => ['nullable', 'boolean'],
            'category_ids'   => ['nullable', 'array'],
            'category_ids.*' => ['numeric', Rule::exists(Category::class, 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('Product::validation.product.title_required'),
            'title.max'      => trans('Product::validation.product.title_max'),
            'slug.regex'     => trans('Product::validation.product.slug_regex'),
            'slug.unique'    => trans('Product::validation.product.slug_unique'),
            'price.required' => trans('Product::validation.product.price_required'),
            'price.min'      => trans('Product::validation.product.price_min'),
            'sale_price.min' => trans('Product::validation.product.sale_price_min'),
        ];
    }
}
