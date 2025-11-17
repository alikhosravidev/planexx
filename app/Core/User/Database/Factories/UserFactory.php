<?php

declare(strict_types=1);

namespace App\Core\User\Database\Factories;

use App\Core\User\Entities\User;
use App\Query\ValueObjects\Email;
use App\Query\ValueObjects\Mobile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('fa_IR');

        return [
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'mobile'             => new Mobile($faker->unique()->mobileNumber()),
            'email'              => new Email($this->faker->unique()->safeEmail()),
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
