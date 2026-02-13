<?php

declare(strict_types=1);

namespace Modules\Product\Database\Factories;

use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\CustomList;

class CustomListFactory extends Factory
{
    protected $model = CustomList::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->unique()->sentence(2),
            'slug'        => $this->faker->unique()->slug(2),
            'description' => $this->faker->optional()->paragraph(),
            'icon_class'  => $this->faker->randomElement(['fa-list', 'fa-table', 'fa-clipboard', 'fa-bars']),
            'color'       => $this->faker->hexColor(),
            'is_active'   => $this->faker->boolean(90),
            'created_by'  => User::factory(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
