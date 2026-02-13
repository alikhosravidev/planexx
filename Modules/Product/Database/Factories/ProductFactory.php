<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductStatusEnum;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $price     = $this->faker->numberBetween(100000, 50000000);
        $salePrice = $this->faker->optional(0.3)->numberBetween(50000, $price);

        return [
            'title'       => $this->faker->unique()->sentence(3),
            'slug'        => $this->faker->unique()->slug(2),
            'price'       => $price,
            'sale_price'  => $salePrice,
            'status'      => $this->faker->randomElement(ProductStatusEnum::cases())->value,
            'is_featured' => $this->faker->boolean(20),
            'created_by'  => User::factory(),
            'updated_by'  => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatusEnum::Active->value,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatusEnum::Draft->value,
        ]);
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatusEnum::Unavailable->value,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
