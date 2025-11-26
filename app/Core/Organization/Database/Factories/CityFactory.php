<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\City;
use App\Core\Organization\Entities\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'province_id' => Province::factory(),
            'name'        => $this->faker->city(),
            'name_en'     => $this->faker->city(),
            'latitude'    => $this->faker->optional()->latitude(),
            'longitude'   => $this->faker->optional()->longitude(),
        ];
    }
}
