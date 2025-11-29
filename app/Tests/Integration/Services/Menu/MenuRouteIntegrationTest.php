<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use Illuminate\Support\Facades\Route;
use Tests\IntegrationTestBase;

final class MenuRouteIntegrationTest extends IntegrationTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();

        Route::get('/dashboard', fn () => 'dashboard')->name('admin.dashboard');
        Route::get('/users/{id}', fn ($id) => "user-{$id}")->name('admin.users.show');
        Route::get('/posts', fn () => 'posts')->name('admin.posts.index');

        app('router')->getRoutes()->refreshNameLookups();
        app('url')->setRoutes(app('router')->getRoutes());
    }

    public function test_generates_url_from_route_name(): void
    {
        $this->manager->register('route.menu', function (MenuBuilder $builder) {
            $builder->item('داشبورد')->route('admin.dashboard');
        });

        $items = $this->manager->withoutCache()->get('route.menu');
        $url   = $items->first()->getUrl();

        $this->assertSame(route('admin.dashboard'), $url);
    }

    public function test_generates_url_from_route_with_parameters(): void
    {
        $this->manager->register('param.menu', function (MenuBuilder $builder) {
            $builder->item('کاربر ۱')->route('admin.users.show', ['id' => 1]);
        });

        $items = $this->manager->withoutCache()->get('param.menu');
        $url   = $items->first()->getUrl();

        $this->assertStringContainsString('/users/1', $url);
    }

    public function test_returns_hash_for_invalid_route(): void
    {
        $this->manager->register('invalid.route.menu', function (MenuBuilder $builder) {
            $builder->item('روت نامعتبر')->route('non.existent.route');
        });

        $items = $this->manager->withoutCache()->get('invalid.route.menu');
        $url   = $items->first()->getUrl();

        $this->assertSame('#', $url);
    }

    public function test_prefers_route_over_url_when_both_set(): void
    {
        $this->manager->register('both.menu', function (MenuBuilder $builder) {
            $builder->item('تست')->url('/static-url')->route('admin.dashboard');
        });

        $items = $this->manager->withoutCache()->get('both.menu');
        $url   = $items->first()->getUrl();

        $this->assertSame(route('admin.dashboard'), $url);
    }
}
