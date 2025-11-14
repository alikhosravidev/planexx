<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Database\Factories;

use App\Core\FormWizard\Entities\FieldValidation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormWizard\Entities\FieldValidation>
 */
class FieldValidationFactory extends Factory
{
    protected $model = FieldValidation::class;

    public function definition(): array
    {
        return [
            'field_id'           => \App\Core\FormWizard\Entities\FormField::factory(),
            'validation_rule_id' => \App\Core\FormWizard\Entities\ValidationRule::factory(),
            'value'              => $this->faker->optional()->word(),
            'error_message'      => $this->faker->optional()->sentence(),
        ];
    }
}
