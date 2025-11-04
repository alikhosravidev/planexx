<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Enums\TierEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPositionFactory extends Factory
{
    protected $model = JobPosition::class;

    public function definition()
    {
        return [
            'title'       => $this->faker->jobTitle(),
            'code'        => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'tier'        => $this->faker->randomElement(TierEnum::cases())->value,
            'image_url'   => $this->faker->optional()->imageUrl(),
            'description' => $this->faker->optional()->paragraph(),
            'is_active'   => $this->faker->boolean(90),
        ];
    }
}
