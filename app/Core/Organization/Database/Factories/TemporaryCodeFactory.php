<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\TemporaryCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemporaryCodeFactory extends Factory
{
    protected $model = TemporaryCode::class;

    public function definition()
    {
        return [
            'user_id'    => null,
            'code'       => rand(10000, 99999),
            'value'      => $this->faker->phoneNumber,
            'expires_at' => now()->addMinutes(5),
        ];
    }
}
