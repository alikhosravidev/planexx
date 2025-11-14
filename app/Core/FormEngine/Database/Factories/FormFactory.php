<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Database\Factories;

use App\Core\FormEngine\Entities\Form;
use App\Core\FormEngine\Enums\AuthTypeEnum;
use App\Core\FormEngine\Enums\DisplayModeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\FormEngine\Entities\Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        return [
            'slug'            => $this->faker->unique()->slug(),
            'title'           => $this->faker->sentence(),
            'subtitle'        => $this->faker->optional()->sentence(),
            'description'     => $this->faker->optional()->paragraph(),
            'display_mode'    => $this->faker->randomElement(DisplayModeEnum::cases()),
            'auth_type'       => $this->faker->randomElement(AuthTypeEnum::cases()),
            'success_message' => $this->faker->optional()->sentence(),
            'redirect_url'    => $this->faker->optional()->url(),
            'max_submissions' => $this->faker->optional()->numberBetween(1, 1000),
            'is_active'       => $this->faker->boolean(),
            'created_by'      => null, // or some user id
        ];
    }
}
