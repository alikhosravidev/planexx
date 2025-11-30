<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repository\BaseRepository;
use App\Entities\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityLogRepository extends BaseRepository
{
    public array $fieldSearchable = [
        'id'       => '=',
        'log_name' => 'like',
    ];

    public function model(): string
    {
        return Activity::class;
    }

    public function getLatestActivities(int $limit = 10): Collection
    {
        return $this->newQuery()
            ->with(['causer', 'subject'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function getOrganizationActivities(int $limit = 10): Collection
    {
        return $this->newQuery()
            ->with(['causer.roles', 'subject'])
            ->whereIn('log_name', ['user', 'department', 'role', 'job_position'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
