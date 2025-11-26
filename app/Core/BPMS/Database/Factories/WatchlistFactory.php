<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Factories;

use App\Core\BPMS\Entities\Task;
use App\Core\BPMS\Entities\Watchlist;
use App\Core\BPMS\Enums\WatchStatus;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchlistFactory extends Factory
{
    protected $model = Watchlist::class;

    public function definition(): array
    {
        return [
            'task_id'      => Task::factory(),
            'watcher_id'   => User::factory(),
            'watch_status' => $this->faker->randomElement(WatchStatus::cases()),
            'watch_reason' => $this->faker->optional()->sentence(),
            'comment'      => $this->faker->optional()->sentence(),
        ];
    }
}
