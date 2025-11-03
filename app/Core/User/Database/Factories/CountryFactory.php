<?php

declare(strict_types=1);

namespace App\Core\User\Database\Factories;

use App\Core\User\Entities\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'name'    => $this->faker->country(),
            'name_en' => $this->faker->country(),
        ];
    }
}
