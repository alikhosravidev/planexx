<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\FieldOption;
use App\Core\FormEngine\Entities\FormField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\FieldOption>
 */
class FieldOptionFactory extends Factory
{
    protected $model = FieldOption::class;

    public function definition(): array
    {
        return [
            'field_id'   => FormField::factory(),
            'label'      => $this->faker->word(),
            'value'      => $this->faker->word(),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
