<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Factories;

use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\Workflow;
use App\Core\BPMS\Entities\WorkflowState;
use App\Core\BPMS\Enums\TaskPriority;
use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'slug'                => $this->faker->unique()->slug(2),
            'title'               => $this->faker->sentence(4),
            'description'         => $this->faker->optional()->paragraph(),
            'workflow_id'         => Workflow::factory(),
            'current_state_id'    => WorkflowState::factory(),
            'assignee_id'         => User::factory(),
            'created_by'          => User::factory(),
            'priority'            => $this->faker->randomElement(TaskPriority::cases()),
            'estimated_hours'     => $this->faker->optional()->randomFloat(2, 0.5, 99.99),
            'due_date'            => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
            'next_follow_up_date' => $this->faker->optional()->dateTimeBetween('now', '+15 days'),
            'completed_at'        => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'completed_at' => now(),
        ]);
    }
}
