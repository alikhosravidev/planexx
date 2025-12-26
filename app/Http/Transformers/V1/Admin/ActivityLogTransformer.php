<?php

declare(strict_types=1);

namespace App\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\Organization\Entities\Department;
use App\Core\Organization\Entities\Role;
use App\Core\Organization\Entities\User;
use App\Entities\Tag;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTransformer extends BaseTransformer
{
    public function transformCollection(Collection $models): array
    {
        return $models->map(fn ($activity) => $this->transformActivity($activity))->toArray();
    }

    private function transformActivity(Activity $activity): array
    {
        return [
            'icon_bg' => $this->getIconBackground($activity),
            'icon'    => $this->getIcon($activity),
            'title'   => $this->getTitle($activity),
            'desc'    => $this->getDescription($activity),
            'time'    => $this->getTimeAgo($activity),
        ];
    }

    protected function getVirtualFieldResolvers(): array
    {
        return [];
    }

    private function getIconBackground(Activity $activity): string
    {
        return match ($activity->subject_type) {
            User::TABLE       => 'bg-green-50',
            Department::TABLE => 'bg-blue-50',
            Role::TABLE       => 'bg-purple-50',
            Tag::TABLE        => 'bg-orange-50',
            default           => 'bg-gray-50',
        };
    }

    private function getIcon(Activity $activity): string
    {
        return match ($activity->subject_type) {
            User::TABLE       => 'fa-solid fa-user-plus text-green-600',
            Department::TABLE => 'fa-solid fa-building text-blue-600',
            Role::TABLE       => 'fa-solid fa-shield-halved text-purple-600',
            Tag::TABLE        => 'fa-solid fa-tag text-orange-600',
            default           => 'fa-solid fa-circle-info text-gray-600',
        };
    }

    private function getTitle(Activity $activity): string
    {
        $entityTitle = match ($activity->subject_type) {
            User::TABLE       => 'کاربر',
            Department::TABLE => 'دیپارتمان',
            Role::TABLE       => 'نقش',
            Tag::TABLE        => 'برچسب',
            default           => $activity->description,
        };

        return match ($activity->event) {
            'created' => "{$entityTitle} جدید اضافه شد",
            'updated' => "{$entityTitle} ویرایش شد",
            'deleted' => "{$entityTitle} حذف شد",
            default   => "فعالیت {$entityTitle}",
        };
    }

    private function getDescription(Activity $activity): string
    {
        $parts = [];

        if ($activity->subject) {
            $subjectName = $this->getSubjectName($activity);

            if ($subjectName) {
                $parts[] = $subjectName;
            }
        }

        if ($activity->subject) {
            $subjectRole = $this->getSubjectRole($activity);

            if ($subjectRole) {
                $parts[] = $subjectRole;
            }
        }

        return implode(' - ', $parts);
    }

    private function getSubjectName(Activity $activity): ?string
    {
        $subject = $activity->subject;

        if (! $subject) {
            return null;
        }

        return match (true) {
            isset($subject->full_name) => $subject->full_name,
            isset($subject->name)      => $subject->name,
            isset($subject->title)     => $subject->title,
            default                    => null,
        };
    }

    private function getSubjectRole(Activity $activity): ?string
    {
        $subject = $activity->subject;

        if (! $subject) {
            return null;
        }

        if (! method_exists($subject, 'relationLoaded') || ! $subject->relationLoaded('roles')) {
            return null;
        }

        $roles = $subject->getRelation('roles');

        return $roles->first()?->name;
    }

    private function getTimeAgo(Activity $activity): string
    {
        return $activity->created_at->diffForHumans();
    }
}
