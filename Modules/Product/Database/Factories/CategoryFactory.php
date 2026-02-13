<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->unique()->word(),
            'slug'       => $this->faker->unique()->slug(2),
            'parent_id'  => null,
            'icon_class' => $this->faker->optional()->randomElement(['fa-folder', 'fa-tag', 'fa-box', 'fa-cube']),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active'  => $this->faker->boolean(90),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withParent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::factory(),
        ]);
    }
}
