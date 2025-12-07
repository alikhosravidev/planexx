<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->company(),
            'manager_id'  => User::factory(),
            'code'        => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'color'       => $this->faker->optional()->safeHexColor(),
            'icon'        => $this->faker->optional()->randomElement(['building', 'users', 'briefcase', 'layers', 'boxes']),
            'description' => $this->faker->optional()->sentence(),
            'is_active'   => $this->faker->boolean(90), // 90% active
        ];
    }
}
