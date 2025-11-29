<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use Spatie\Permission\Models\Permission;
use Tests\IntegrationTestBase;

final class MenuPermissionTest extends IntegrationTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();
    }

    public function test_filters_items_when_user_has_no_permission(): void
    {
        $this->actingAsClient();
        Permission::findOrCreate('admin.access', 'web');
        Permission::findOrCreate('users.view', 'web');

        $this->manager->register('permission.menu', function (MenuBuilder $builder) {
            $builder->item('عمومی', 'public-item')->url('/public');
            $builder->item('ادمین', 'admin-item')->url('/admin')->permission('admin.access');
            $builder->item('کاربران', 'users-item')->url('/users')->permission('users.view');
        });

        $items = $this->manager->withoutCache()->get('permission.menu');

        $this->assertCount(1, $items);
        $this->assertSame('public-item', $items->first()->getId());
    }

    public function test_shows_items_when_user_has_permission(): void
    {
        $this->actingAsClient();
        Permission::findOrCreate('admin.access', 'web');
        $this->user->givePermissionTo('admin.access');

        $this->manager->register('permission.menu', function (MenuBuilder $builder) {
            $builder->item('ادمین', 'admin-item')->permission('admin.access');
        });

        $items = $this->manager->withoutCache()->get('permission.menu');

        $this->assertCount(1, $items);
        $this->assertSame('admin-item', $items->first()->getId());
    }

    public function test_hides_all_items_from_guest_users(): void
    {
        $this->manager->register('guest.menu', function (MenuBuilder $builder) {
            $builder->item('محافظت شده', 'protected')->permission('any.permission');
        });

        $items = $this->manager->withoutCache()->get('guest.menu');
        $this->assertCount(0, $items);
    }

    public function test_filters_children_based_on_permissions(): void
    {
        $this->actingAsClient();
        Permission::findOrCreate('parent.view', 'web');
        Permission::findOrCreate('child.allowed', 'web');
        Permission::findOrCreate('child.denied', 'web');
        $this->user->givePermissionTo('parent.view');
        $this->user->givePermissionTo('child.allowed');

        $this->manager->register('nested.permission.menu', function (MenuBuilder $builder) {
            $builder->group('والد', 'parent')->permission('parent.view')->children(function (MenuBuilder $sub) {
                $sub->item('فرزند مجاز', 'allowed-child')->permission('child.allowed');
                $sub->item('فرزند غیرمجاز', 'denied-child')->permission('child.denied');
            });
        });

        $items    = $this->manager->withoutCache()->get('nested.permission.menu');
        $parent   = $items->first();
        $children = $parent->getChildren();

        $this->assertCount(1, $children);
        $this->assertSame('allowed-child', $children[0]->getId());
    }
}
