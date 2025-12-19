<?php

declare(strict_types=1);

namespace App\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Entities\Tag;
use App\Repositories\TagRepository;
use App\Services\Stats\StatBuilder;

class TagStatsRegistrar implements RegistrarInterface
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('app.tag.stats', function (StatBuilder $builder) {
            $stats = $this->tagRepository
                ->newQuery()
                ->selectRaw('COUNT(DISTINCT ' . Tag::TABLE . '.id) as total_tags')
                ->selectRaw('COUNT(DISTINCT ' . Tag::ENTITY_PIVOT_TABLE . '.entity_id) as tagged_entities')
                ->selectRaw('COUNT(DISTINCT ' . Tag::ENTITY_PIVOT_TABLE . '.entity_type) as entity_types')
                ->leftJoin(
                    Tag::ENTITY_PIVOT_TABLE,
                    Tag::TABLE . '.id',
                    '=',
                    Tag::ENTITY_PIVOT_TABLE . '.tag_id'
                )
                ->first();

            $builder->stat('کل برچسب‌ها', 'app-total-tags')
                ->value($stats->total_tags)
                ->icon('fa-solid fa-tags')
                ->color('blue')
                ->order(1);

            $builder->stat('موجودیت‌های برچسب زده', 'app-tagged-entities')
                ->value($stats->tagged_entities ?? 0)
                ->icon('fa-solid fa-tag')
                ->color('green')
                ->order(2);

            $builder->stat('انواع موجودیت', 'app-entity-types')
                ->value($stats->entity_types ?? 0)
                ->icon('fa-solid fa-database')
                ->color('purple')
                ->order(3);

            $builder->stat('پرکاربردترین', 'app-most-used-tag')
                ->value(0)
                ->icon('fa-solid fa-fire')
                ->color('orange')
                ->order(4);
        });
    }
}
