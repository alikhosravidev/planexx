<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Database\Factories;

use App\Core\FormWizard\Entities\FormField;
use App\Core\FormWizard\Enums\FieldTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormWizard\Entities\FormField>
 */
class FormFieldFactory extends Factory
{
    protected $model = FormField::class;

    public function definition(): array
    {
        return [
            'form_id'       => \App\Core\FormWizard\Entities\Form::factory(),
            'field_key'     => $this->faker->unique()->word(),
            'field_type'    => $this->faker->randomElement(FieldTypeEnum::cases()),
            'label'         => $this->faker->sentence(),
            'placeholder'   => $this->faker->optional()->sentence(),
            'default_value' => $this->faker->optional()->word(),
            'order'         => $this->faker->numberBetween(1, 10),
            'is_required'   => $this->faker->boolean(),
            'payload'       => $this->faker->optional()->randomElements(['key' => 'value'], 2),
            'is_active'     => $this->faker->boolean(),
        ];
    }
}
