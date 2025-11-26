<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Factories;

use App\Core\BPMS\Entities\FollowUp;
use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\FollowUpType;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowUpFactory extends Factory
{
    protected $model = FollowUp::class;

    public function definition(): array
    {
        return [
            'task_id'              => Task::factory(),
            'type'                 => $this->faker->randomElement(FollowUpType::cases()),
            'content'              => $this->faker->optional()->paragraph(),
            'created_by'           => User::factory(),
            'previous_assignee_id' => $this->faker->boolean(30) ? User::factory() : null,
            'new_assignee_id'      => $this->faker->boolean(30) ? User::factory() : null,
            'previous_state_id'    => $this->faker->boolean(30) ? WorkflowState::factory() : null,
            'new_state_id'         => $this->faker->boolean(30) ? WorkflowState::factory() : null,
            'created_at'           => now(),
        ];
    }
}
