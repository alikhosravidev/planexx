<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\Country;
use App\Core\Organization\Entities\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'name'       => $this->faker->state(),
            'name_en'    => $this->faker->state(),
            'latitude'   => $this->faker->optional()->latitude(),
            'longitude'  => $this->faker->optional()->longitude(),
        ];
    }
}
