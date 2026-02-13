<?php

declare(strict_types=1);

namespace Modules\Product\DTOs;

use App\ValueObjects\Price;
use Illuminate\Contracts\Support\Arrayable;
use Modules\Product\Collections\CategoryIdCollection;
use Modules\Product\Enums\ProductStatusEnum;

final readonly class ProductDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public Price $price,
        public ?string $slug = null,
        public ?Price $salePrice = null,
        public ProductStatusEnum $status = ProductStatusEnum::Draft,
        public bool $isFeatured = false,
        public CategoryIdCollection $categoryIds = new CategoryIdCollection(),
    ) {
    }

    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'slug'        => $this->slug,
            'price'       => $this->price->value,
            'sale_price'  => $this->salePrice?->value,
            'status'      => $this->status->value,
            'is_featured' => $this->isFeatured,
        ];
    }
}
