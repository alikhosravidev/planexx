<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Distribution;

use App\Services\Distribution\DistributionBuilder;
use App\Services\Distribution\DistributionManager;
use Illuminate\Support\Facades\Cache;
use Tests\IntegrationTestBase;

final class DistributionCacheTest extends IntegrationTestBase
{
    private DistributionManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new DistributionManager();
        $this->manager->withCache();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_caches_distribution_by_default(): void
    {
        $this->manager->register('cached.distribution', function (DistributionBuilder $builder) {
            $builder->segment('تست', 'test')->value('۱۰۰۰')->percent(25);
        });

        $this->manager->get('cached.distribution');
        $this->manager->get('cached.distribution');

        $cacheKey = $this->getCacheKey('cached.distribution');
        $this->assertTrue(Cache::has($cacheKey));
    }

    public function test_bypasses_cache_when_disabled(): void
    {
        $this->manager->register('no-cache.distribution', function (DistributionBuilder $builder) {
            $builder->segment('تست', 'test')->value('۱۰۰۰')->percent(25);
        });

        $this->manager->withoutCache()->get('no-cache.distribution');

        $cacheKey = $this->getCacheKey('no-cache.distribution');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_specific_distribution_cache(): void
    {
        $this->manager->register('clearable.distribution', function (DistributionBuilder $builder) {
            $builder->segment('تست', 'test')->value('۱۰۰۰')->percent(25);
        });

        $this->manager->get('clearable.distribution');
        $cacheKey = $this->getCacheKey('clearable.distribution');
        $this->assertTrue(Cache::has($cacheKey));

        $this->manager->clearCache('clearable.distribution');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_all_distribution_cache(): void
    {
        $this->manager->register('distribution1', function (DistributionBuilder $builder) {
            $builder->segment('تست ۱', 'test1')->value('۱۰۰۰')->percent(50);
        });
        $this->manager->register('distribution2', function (DistributionBuilder $builder) {
            $builder->segment('تست ۲', 'test2')->value('۲۰۰۰')->percent(50);
        });

        $this->manager->get('distribution1');
        $this->manager->get('distribution2');

        $this->manager->clearCache();

        $this->assertFalse(Cache::has($this->getCacheKey('distribution1')));
        $this->assertFalse(Cache::has($this->getCacheKey('distribution2')));
    }

    private function getCacheKey(string $distributionName): string
    {
        $userId = auth()->id() ?? 'guest';

        return 'distribution_' . $distributionName . '_' . $userId;
    }
}
