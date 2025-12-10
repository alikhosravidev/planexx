<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Entity\RoleableEntity;
use App\Contracts\Repository\BaseRepository;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\Role;
use App\Core\Organization\Entities\User;
use App\Entities\Activity;
use App\Entities\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
            ->with([
                'causer',
                'subject' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        RoleableEntity::class => ['roles'],
                    ]);
                },
            ])
            ->where('log_name', '=', Activity::DEFAULT_LOG_NAME)
            ->whereIn('subject_type', [Department::TABLE, Role::TABLE, User::TABLE, Tag::TABLE])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
