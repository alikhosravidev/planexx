<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\PersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalAccessTokenFactory extends Factory
{
    protected $model = PersonalAccessToken::class;

    public function definition(): array
    {
        return [
            'name'        => 'auth',
            'token'       => $this->faker->uuid,
            'abilities'   => ['*'],
            'user_agent'  => $this->faker->userAgent(),
            'fingerprint' => $this->faker->uuid,
            'ip'          => $this->faker->ipv4(),
        ];
    }
}
