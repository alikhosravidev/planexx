<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Database\Factories;

use App\Core\FormWizard\Entities\FieldOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormWizard\Entities\FieldOption>
 */
class FieldOptionFactory extends Factory
{
    protected $model = FieldOption::class;

    public function definition(): array
    {
        return [
            'field_id'   => \App\Core\FormWizard\Entities\FormField::factory(),
            'label'      => $this->faker->word(),
            'value'      => $this->faker->word(),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
