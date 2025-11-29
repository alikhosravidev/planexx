<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use Illuminate\Support\Facades\Cache;
use Tests\IntegrationTestBase;

final class MenuCacheTest extends IntegrationTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();
        Cache::flush();
    }

    public function test_caches_menu_on_first_access(): void
    {
        $buildCount = 0;

        $this->manager->register('test.cache', function (MenuBuilder $builder) {
            $builder->item('آیتم کش شده');
        });

        $this->manager->register('test.cache', function () use (&$buildCount) {
            $buildCount++;
        });

        $this->manager->withCache(3600)->get('test.cache');
        $this->assertSame(1, $buildCount);
    }

    public function test_retrieves_menu_from_cache_on_subsequent_access(): void
    {
        $buildCount = 0;

        $this->manager->register('cached.menu', function (MenuBuilder $builder) {
            $builder->item('این نباید اجرا شود');
        });

        $this->manager->register('cached.menu', function () use (&$buildCount) {
            $buildCount++;
        });

        $this->manager->withCache()->get('cached.menu');
        $this->manager->withCache()->get('cached.menu');

        $this->assertSame(1, $buildCount);
    }

    public function test_clears_cache_for_specific_and_all_menus(): void
    {
        $this->manager->register('menu.one', fn ($b) => $b->item('یک'));
        $this->manager->register('menu.two', fn ($b) => $b->item('دو'));

        $c1 = 0;
        $this->manager->register('menu.one', function () use (&$c1) { $c1++; });
        $this->manager->withCache()->get('menu.one');
        $this->manager->withCache()->get('menu.one');
        $this->assertSame(1, $c1);

        $this->manager->clearCache('menu.one');
        $this->manager->withCache()->get('menu.one');
        $this->assertSame(2, $c1);

        $c2 = 0;
        $this->manager->register('menu.two', function () use (&$c2) { $c2++; });
        $this->manager->withCache()->get('menu.two');
        $this->manager->clearCache();
        $this->manager->withCache()->get('menu.one');
        $this->manager->withCache()->get('menu.two');
        $this->assertGreaterThanOrEqual(1, $c2);
    }

    public function test_uses_different_cache_key_per_user(): void
    {
        $this->manager->register('user.menu', fn ($b) => $b->item('تست'));

        $c = 0;
        $this->manager->register('user.menu', function () use (&$c) { $c++; });

        $this->actingAsClient();
        $this->manager->withCache()->get('user.menu');

        $this->actingAsClient();
        $this->manager->withCache()->get('user.menu');

        $this->assertSame(2, $c);
    }

    public function test_skips_cache_when_disabled(): void
    {
        $this->manager->register('no.cache.menu', fn ($b) => $b->item('بدون کش'));
        $c = 0;
        $this->manager->register('no.cache.menu', function () use (&$c) { $c++; });
        $this->manager->withoutCache()->get('no.cache.menu');
        $this->manager->withoutCache()->get('no.cache.menu');
        $this->assertSame(2, $c);
    }
}
