<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\QuickAccess;

use App\Services\QuickAccess\QuickAccessBuilder;
use App\Services\QuickAccess\QuickAccessManager;
use Illuminate\Support\Facades\Cache;
use Tests\IntegrationTestBase;

final class QuickAccessCacheTest extends IntegrationTestBase
{
    private QuickAccessManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new QuickAccessManager();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_caches_quick_access_by_default(): void
    {
        $this->manager->register('cached.quick', function (QuickAccessBuilder $builder) {
            $builder->item('تست', 'test')->url('/test');
        });

        $this->manager->get('cached.quick');
        $this->manager->get('cached.quick');

        $cacheKey = $this->getCacheKey('cached.quick');
        $this->assertTrue(Cache::has($cacheKey));
    }

    public function test_bypasses_cache_when_disabled(): void
    {
        $this->manager->register('no-cache.quick', function (QuickAccessBuilder $builder) {
            $builder->item('تست', 'test')->url('/test');
        });

        $this->manager->withoutCache()->get('no-cache.quick');

        $cacheKey = $this->getCacheKey('no-cache.quick');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_specific_quick_access_cache(): void
    {
        $this->manager->register('clearable.quick', function (QuickAccessBuilder $builder) {
            $builder->item('تست', 'test')->url('/test');
        });

        $this->manager->get('clearable.quick');
        $cacheKey = $this->getCacheKey('clearable.quick');
        $this->assertTrue(Cache::has($cacheKey));

        $this->manager->clearCache('clearable.quick');
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_clears_all_quick_access_cache(): void
    {
        $this->manager->register('quick1', function (QuickAccessBuilder $builder) {
            $builder->item('تست ۱', 'test1')->url('/test1');
        });
        $this->manager->register('quick2', function (QuickAccessBuilder $builder) {
            $builder->item('تست ۲', 'test2')->url('/test2');
        });

        $this->manager->get('quick1');
        $this->manager->get('quick2');

        $this->manager->clearCache();

        $this->assertFalse(Cache::has($this->getCacheKey('quick1')));
        $this->assertFalse(Cache::has($this->getCacheKey('quick2')));
    }

    private function getCacheKey(string $quickAccessName): string
    {
        $userId = auth()->id() ?? 'guest';

        return 'quick_access_' . $quickAccessName . '_' . $userId;
    }
}
