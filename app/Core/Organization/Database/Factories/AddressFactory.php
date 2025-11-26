<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\Address;
use App\Core\Organization\Entities\City;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'country_id'      => null,
            'province_id'     => null,
            'city_id'         => null,
            'receiver_name'   => $this->faker->name(),
            'receiver_mobile' => $this->faker->numerify('09#########'),
            'address'         => $this->faker->streetAddress(),
            'postal_code'     => $this->faker->numerify('##########'),
            'latitude'        => $this->faker->optional()->latitude(),
            'longitude'       => $this->faker->optional()->longitude(),
            'is_verified'     => false,
        ];
    }

    public function withCity(): self
    {
        return $this->state(function () {
            $city = City::factory()->create();

            return [
                'city_id'     => $city->id,
                'province_id' => $city->province_id,
                // country_id can be resolved via province->country in service under test
            ];
        });
    }
}
