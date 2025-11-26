<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Factories;

use App\Core\BPMS\Entities\Workflow;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->unique()->sentence(3),
            'slug'          => $this->faker->unique()->slug(2),
            'description'   => $this->faker->optional()->paragraph(),
            'department_id' => Department::factory(),
            'owner_id'      => User::factory(),
            'created_by'    => User::factory(),
            'is_active'     => $this->faker->boolean(90),
        ];
    }
}
