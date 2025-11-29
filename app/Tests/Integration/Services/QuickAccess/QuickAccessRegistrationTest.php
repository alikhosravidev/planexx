<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\QuickAccess;

use App\Services\QuickAccess\QuickAccessBuilder;
use App\Services\QuickAccess\QuickAccessManager;
use Tests\IntegrationTestBase;

final class QuickAccessRegistrationTest extends IntegrationTestBase
{
    private QuickAccessManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new QuickAccessManager();
    }

    public function test_registers_complete_dashboard_quick_access(): void
    {
        $this->manager->register('admin.dashboard', function (QuickAccessBuilder $builder) {
            $builder->item('ایجاد کاربر', 'create-user')
                ->url('/admin/users/create')
                ->icon('heroicon-o-user-plus')
                ->color('blue')
                ->order(1);

            $builder->item('افزودن محصول', 'add-product')
                ->url('/admin/products/create')
                ->icon('heroicon-o-plus-circle')
                ->color('green')
                ->order(2);

            $builder->item('گزارش جدید', 'new-report')
                ->url('/admin/reports/create')
                ->icon('heroicon-o-document-text')
                ->color('yellow')
                ->order(3);

            $builder->item('تنظیمات', 'settings')
                ->url('/admin/settings')
                ->icon('heroicon-o-cog')
                ->color('gray')
                ->order(4);
        });

        $items = $this->manager->withoutCache()->get('admin.dashboard');
        $array = $this->manager->withoutCache()->toArray('admin.dashboard');

        $this->assertCount(4, $items);
        $this->assertSame('create-user', $items->first()->getId());
        $this->assertIsArray($array);
        $this->assertSame('/admin/users/create', $array[0]['url']);
    }

    public function test_registers_quick_access_from_multiple_modules(): void
    {
        $this->manager->register('admin.dashboard', function (QuickAccessBuilder $builder) {
            $builder->item('ایجاد کاربر', 'create-user')->url('/users/create')->order(1);
        });

        $this->manager->register('admin.dashboard', function (QuickAccessBuilder $builder) {
            $builder->item('افزودن محصول', 'add-product')->url('/products/create')->order(2);
        });

        $this->manager->register('admin.dashboard', function (QuickAccessBuilder $builder) {
            $builder->item('گزارش جدید', 'new-report')->url('/reports/create')->order(3);
        });

        $items = $this->manager->withoutCache()->get('admin.dashboard');

        $this->assertCount(3, $items);
        $this->assertSame('create-user', $items[0]->getId());
        $this->assertSame('add-product', $items[1]->getId());
        $this->assertSame('new-report', $items[2]->getId());
    }

    public function test_handles_empty_and_non_existent_quick_access(): void
    {
        $this->manager->register('empty.quick', function (QuickAccessBuilder $builder) {});
        $items = $this->manager->withoutCache()->get('empty.quick');
        $this->assertCount(0, $items);
        $this->assertTrue($items->isEmpty());

        $items2 = $this->manager->withoutCache()->get('non.existent');
        $this->assertCount(0, $items2);
    }

    public function test_registers_quick_access_with_conditional_items(): void
    {
        $canCreateUser = true;
        $canDeleteUser = false;

        $this->manager->register('conditional.quick', function (QuickAccessBuilder $builder) use ($canCreateUser, $canDeleteUser) {
            $builder->item('همیشه نمایش', 'always')->url('/always')->order(1);

            if ($canCreateUser) {
                $builder->item('ایجاد کاربر', 'create-user')->url('/users/create')->order(2);
            }

            if ($canDeleteUser) {
                $builder->item('حذف کاربر', 'delete-user')->url('/users/delete')->order(3);
            }

            $builder->item('پویا', 'dynamic')->activeWhen(fn () => true)->url('/dynamic')->order(4);
        });

        $items = $this->manager->withoutCache()->get('conditional.quick');
        $this->assertCount(3, $items);
        $ids = $items->map(fn ($i) => $i->getId())->toArray();
        $this->assertContains('always', $ids);
        $this->assertContains('create-user', $ids);
        $this->assertNotContains('delete-user', $ids);
    }

    public function test_sorts_quick_access_correctly_across_multiple_registrations(): void
    {
        $this->manager->register('sorted.quick', function (QuickAccessBuilder $builder) {
            $builder->item('سوم', 'third')->order(30);
        });

        $this->manager->register('sorted.quick', function (QuickAccessBuilder $builder) {
            $builder->item('اول', 'first')->order(10);
        });

        $this->manager->register('sorted.quick', function (QuickAccessBuilder $builder) {
            $builder->item('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.quick');
        $ids   = $items->map(fn ($i) => $i->getId())->toArray();

        $this->assertSame(['first', 'second', 'third'], $ids);
    }

    public function test_converts_complex_quick_access_to_json(): void
    {
        $this->manager->register('json.quick', function (QuickAccessBuilder $builder) {
            $builder->item('ایجاد', 'create')
                ->url('/create')
                ->icon('plus')
                ->color('blue')
                ->enabled(true)
                ->target('_self');
        });

        $json    = $this->manager->withoutCache()->toJson('json.quick');
        $decoded = json_decode($json, true);

        $this->assertNotNull($decoded);
        $this->assertIsArray($decoded);
        $this->assertCount(1, $decoded);
        $this->assertSame('create', $decoded[0]['id']);
        $this->assertSame('/create', $decoded[0]['url']);
        $this->assertTrue($decoded[0]['enabled']);
    }

    public function test_handles_enabled_state_in_quick_access(): void
    {
        $this->manager->register('enabled.quick', function (QuickAccessBuilder $builder) {
            $builder->item('فعال', 'enabled-item')->enabled(true)->url('/enabled');
            $builder->item('غیرفعال', 'disabled-item')->enabled(false)->url('/disabled');
            $builder->item('شرطی فعال', 'conditional-enabled')->enabledWhen(fn () => true)->url('/conditional');
            $builder->item('شرطی غیرفعال', 'conditional-disabled')->enabledWhen(fn () => false)->url('/conditional-disabled');
        });

        $array = $this->manager->withoutCache()->toArray('enabled.quick');

        $this->assertCount(4, $array);
        $this->assertTrue($array[0]['enabled']);
        $this->assertFalse($array[1]['enabled']);
        $this->assertTrue($array[2]['enabled']);
        $this->assertFalse($array[3]['enabled']);
    }
}
