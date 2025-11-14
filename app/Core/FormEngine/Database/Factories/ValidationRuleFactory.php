<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\ValidationRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\ValidationRule>
 */
class ValidationRuleFactory extends Factory
{
    protected $model = ValidationRule::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
