<?php

declare(strict_types=1);

namespace App\Core\User\Database\Factories;

use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'full_name'          => $this->faker->name(),
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'mobile'             => $this->faker->unique()->phoneNumber(),
            'email'              => $this->faker->unique()->safeEmail(),
            'email_verified_at'  => $this->faker->optional()->dateTime(),
            'mobile_verified_at' => $this->faker->optional()->dateTime(),
            'password'           => 'password',
        ];
    }
}
