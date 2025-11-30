<?php

declare(strict_types=1);

namespace App\Http\Transformers\Web;

use App\Contracts\Transformer\BaseTransformer;
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
        return match ($activity->log_name) {
            'user'         => 'bg-green-50',
            'department'   => 'bg-blue-50',
            'role'         => 'bg-purple-50',
            'job_position' => 'bg-orange-50',
            default        => 'bg-gray-50',
        };
    }

    private function getIcon(Activity $activity): string
    {
        return match ($activity->log_name) {
            'user'         => 'fa-solid fa-user-plus text-green-600',
            'department'   => 'fa-solid fa-building text-blue-600',
            'role'         => 'fa-solid fa-shield-halved text-purple-600',
            'job_position' => 'fa-solid fa-briefcase text-orange-600',
            default        => 'fa-solid fa-circle-info text-gray-600',
        };
    }

    private function getTitle(Activity $activity): string
    {
        return match ($activity->log_name) {
            'user'         => $this->getUserTitle($activity),
            'department'   => $this->getDepartmentTitle($activity),
            'role'         => $this->getRoleTitle($activity),
            'job_position' => $this->getJobPositionTitle($activity),
            default        => $activity->description,
        };
    }

    private function getUserTitle(Activity $activity): string
    {
        return match ($activity->event) {
            'created' => 'کاربر جدید اضافه شد',
            'updated' => 'کاربر ویرایش شد',
            'deleted' => 'کاربر حذف شد',
            default   => 'فعالیت کاربر',
        };
    }

    private function getDepartmentTitle(Activity $activity): string
    {
        return match ($activity->event) {
            'created' => 'دپارتمان جدید ایجاد شد',
            'updated' => 'دپارتمان ویرایش شد',
            'deleted' => 'دپارتمان حذف شد',
            default   => 'فعالیت دپارتمان',
        };
    }

    private function getRoleTitle(Activity $activity): string
    {
        return match ($activity->event) {
            'created' => 'نقش جدید تعریف شد',
            'updated' => 'نقش ویرایش شد',
            'deleted' => 'نقش حذف شد',
            default   => 'فعالیت نقش',
        };
    }

    private function getJobPositionTitle(Activity $activity): string
    {
        return match ($activity->event) {
            'created' => 'موقعیت شغلی جدید',
            'updated' => 'موقعیت شغلی ویرایش شد',
            'deleted' => 'موقعیت شغلی حذف شد',
            default   => 'فعالیت موقعیت شغلی',
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

        if ($activity->causer) {
            $causerRole = $this->getCauserRole($activity);

            if ($causerRole) {
                $parts[] = $causerRole;
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

    private function getCauserRole(Activity $activity): ?string
    {
        $causer = $activity->causer;

        if (! $causer || ! empty($causer->roles)) {
            return null;
        }

        return $causer->roles->first()?->name;
    }

    private function getTimeAgo(Activity $activity): string
    {
        return $activity->created_at->diffForHumans();
    }
}
