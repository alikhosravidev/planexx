<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Factories;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Entities\UserDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDepartmentFactory extends Factory
{
    protected $model = UserDepartment::class;

    public function definition()
    {
        return [
            'user_id'       => User::factory(),
            'department_id' => Department::factory(),
            'is_primary'    => $this->faker->boolean(20), // 20% primary
        ];
    }
}
