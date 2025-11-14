<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\FieldValidation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\FieldValidation>
 */
class FieldValidationFactory extends Factory
{
    protected $model = FieldValidation::class;

    public function definition(): array
    {
        return [
            'field_id'           => \App\Core\FormEngine\Entities\FormField::factory(),
            'validation_rule_id' => \App\Core\FormEngine\Entities\ValidationRule::factory(),
            'value'              => $this->faker->optional()->word(),
            'error_message'      => $this->faker->optional()->sentence(),
        ];
    }
}
