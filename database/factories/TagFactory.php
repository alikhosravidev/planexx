<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Entities\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(asText: true);

        return [
            'name' => $name,
            // Leave slug null sometimes to test auto-generation
            'slug'        => $this->faker->boolean(60) ? null : $this->faker->slug(),
            'description' => $this->faker->optional()->paragraph(),
            'color'       => $this->faker->optional()->hexColor(),
            'icon'        => $this->faker->optional()->randomElement(['fa-tag', 'fa-hashtag', 'fa-bookmark', 'fa-star']),
            'usage_count' => $this->faker->numberBetween(0, 500),
        ];
    }
}
