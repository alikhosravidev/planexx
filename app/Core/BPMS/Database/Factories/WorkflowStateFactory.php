<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Factories;

use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\WorkflowStatePosition;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowStateFactory extends Factory
{
    protected $model = WorkflowState::class;

    public function definition(): array
    {
        return [
            'workflow_id'         => Workflow::factory(),
            'name'                => $this->faker->words(2, true),
            'slug'                => $this->faker->unique()->slug(2),
            'description'         => $this->faker->optional()->sentence(),
            'color'               => $this->faker->optional()->hexColor(),
            'order'               => $this->faker->numberBetween(1, 10),
            'position'            => $this->faker->randomElement(WorkflowStatePosition::cases()),
            'default_assignee_id' => $this->faker->boolean(50) ? User::factory() : null,
            'is_active'           => $this->faker->boolean(95),
        ];
    }
}
