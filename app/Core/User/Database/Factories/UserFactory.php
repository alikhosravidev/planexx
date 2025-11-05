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
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'mobile'             => substr($this->faker->unique()->phoneNumber(), 0, 15),
            'email'              => $this->faker->unique()->safeEmail(),
            'email_verified_at'  => $this->faker->optional()->dateTime(),
            'mobile_verified_at' => $this->faker->optional()->dateTime(),
            'password'           => 'password',
        ];
    }

    public function verified(): Factory
    {
        return $this->state(
            [
                'email_verified_at'  => $this->faker->optional()->dateTime(),
                'mobile_verified_at' => $this->faker->optional()->dateTime(),
            ]
        );
    }

    public function mobileVerified(): Factory
    {
        return $this->state(
            [
                'mobile_verified_at' => $this->faker->optional()->dateTime(),
            ]
        );
    }

    public function emailVerified(): Factory
    {
        return $this->state(
            [
                'email_verified_at' => $this->faker->optional()->dateTime(),
            ]
        );
    }

    public function withPosition(string $positionName): Factory
    {
        return $this;
    }
}
