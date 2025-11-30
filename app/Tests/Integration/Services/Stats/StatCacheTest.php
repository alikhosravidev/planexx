<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Stats;

use App\Services\Stats\StatBuilder;
use App\Services\Stats\StatManager;
use Illuminate\Support\Facades\Cache;
use Tests\IntegrationTestBase;

final class StatCacheTest extends IntegrationTestBase
{
    private StatManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new StatManager();
        $this->manager->withCache();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_caches_stats_by_default(): void
    {
        $this->manager->register('cached.stats', function (StatBuilder $builder) {
            $builder->stat('تست', 'test')->value('۱۰۰');
        });

        $this->manager->get('cached.stats');
        $this->manager->get('cached.stats');

        $cacheKey = $this->getCacheKey('cached.stats');
        $this->assertTrue(Cache::has($cacheKey));
    }

    public function test_bypasses_cache_when_disabled(): void
    {
        $this->manager->register('no-cache.stats', function (StatBuilder $builder) {
            $builder->stat('تست', 'test')->value('۱۰۰');
        });

        $this->manager->withoutCache()->get('no-cache.stats');

        $cacheKey = $this->getCacheKey('no-cache.stats');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_specific_stats_cache(): void
    {
        $this->manager->register('clearable.stats', function (StatBuilder $builder) {
            $builder->stat('تست', 'test')->value('۱۰۰');
        });

        $this->manager->get('clearable.stats');
        $cacheKey = $this->getCacheKey('clearable.stats');
        $this->assertTrue(Cache::has($cacheKey));

        $this->manager->clearCache('clearable.stats');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_all_stats_cache(): void
    {
        $this->manager->register('stats1', function (StatBuilder $builder) {
            $builder->stat('تست ۱', 'test1');
        });
        $this->manager->register('stats2', function (StatBuilder $builder) {
            $builder->stat('تست ۲', 'test2');
        });

        $this->manager->get('stats1');
        $this->manager->get('stats2');

        $this->manager->clearCache();

        $this->assertFalse(Cache::has($this->getCacheKey('stats1')));
        $this->assertFalse(Cache::has($this->getCacheKey('stats2')));
    }

    private function getCacheKey(string $statsName): string
    {
        $userId = auth()->id() ?? 'guest';

        return 'stats_' . $statsName . '_' . $userId;
    }
}
