<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Stats;

use App\Services\Stats\StatBuilder;
use App\Services\Stats\StatManager;
use Tests\IntegrationTestBase;

final class StatRegistrationTest extends IntegrationTestBase
{
    private StatManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new StatManager();
    }

    public function test_registers_complete_dashboard_stats(): void
    {
        $this->manager->register('admin.dashboard', function (StatBuilder $builder) {
            $builder->stat('کل فروش', 'total-sales')
                ->value('۱۲۵,۰۰۰ تومان')
                ->description('در این ماه')
                ->icon('heroicon-o-currency-dollar')
                ->color('green')
                ->change('+۱۵٪', 'increase')
                ->order(1);

            $builder->stat('کاربران جدید', 'new-users')
                ->value('۲۳۴')
                ->description('نسبت به ماه گذشته')
                ->icon('heroicon-o-users')
                ->color('blue')
                ->change('+۸٪', 'increase')
                ->order(2);

            $builder->stat('سفارشات', 'orders')
                ->value('۵۶')
                ->description('در انتظار پردازش')
                ->icon('heroicon-o-shopping-cart')
                ->color('yellow')
                ->change('-۳٪', 'decrease')
                ->order(3);

            $builder->stat('درآمد خالص', 'net-income')
                ->value('۸۵,۰۰۰ تومان')
                ->description('پس از کسر هزینه‌ها')
                ->icon('heroicon-o-chart-bar')
                ->color('purple')
                ->change('+۱۲٪', 'increase')
                ->order(4);
        });

        $items = $this->manager->withoutCache()->get('admin.dashboard');
        $array = $this->manager->withoutCache()->toArray('admin.dashboard');

        $this->assertCount(4, $items);
        $this->assertSame('total-sales', $items->first()->getId());
        $this->assertIsArray($array);
        $this->assertSame('۱۲۵,۰۰۰ تومان', $array[0]['value']);
    }

    public function test_registers_stats_from_multiple_modules(): void
    {
        $this->manager->register('admin.dashboard', function (StatBuilder $builder) {
            $builder->stat('فروش', 'sales')->value('۱۰۰,۰۰۰')->order(1);
        });

        $this->manager->register('admin.dashboard', function (StatBuilder $builder) {
            $builder->stat('کاربران', 'users')->value('۵۰۰')->order(2);
        });

        $this->manager->register('admin.dashboard', function (StatBuilder $builder) {
            $builder->stat('محصولات', 'products')->value('۱۲۰')->order(3);
        });

        $items = $this->manager->withoutCache()->get('admin.dashboard');

        $this->assertCount(3, $items);
        $this->assertSame('sales', $items[0]->getId());
        $this->assertSame('users', $items[1]->getId());
        $this->assertSame('products', $items[2]->getId());
    }

    public function test_handles_empty_and_non_existent_stats(): void
    {
        $this->manager->register('empty.stats', function (StatBuilder $builder) {});
        $items = $this->manager->withoutCache()->get('empty.stats');
        $this->assertCount(0, $items);
        $this->assertTrue($items->isEmpty());

        $items2 = $this->manager->withoutCache()->get('non.existent');
        $this->assertCount(0, $items2);
    }

    public function test_registers_stats_with_conditional_items(): void
    {
        $showSales   = true;
        $showRevenue = false;

        $this->manager->register('conditional.stats', function (StatBuilder $builder) use ($showSales, $showRevenue) {
            $builder->stat('همیشه نمایش', 'always')->value('۱۰۰')->order(1);

            if ($showSales) {
                $builder->stat('فروش', 'sales')->value('۵۰,۰۰۰')->order(2);
            }

            if ($showRevenue) {
                $builder->stat('درآمد', 'revenue')->value('۳۰,۰۰۰')->order(3);
            }

            $builder->stat('پویا', 'dynamic')->activeWhen(fn () => true)->value('۲۰۰')->order(4);
        });

        $items = $this->manager->withoutCache()->get('conditional.stats');
        $this->assertCount(3, $items);
        $ids = $items->map(fn ($i) => $i->getId())->toArray();
        $this->assertContains('always', $ids);
        $this->assertContains('sales', $ids);
        $this->assertNotContains('revenue', $ids);
    }

    public function test_sorts_stats_correctly_across_multiple_registrations(): void
    {
        $this->manager->register('sorted.stats', function (StatBuilder $builder) {
            $builder->stat('سوم', 'third')->order(30);
        });

        $this->manager->register('sorted.stats', function (StatBuilder $builder) {
            $builder->stat('اول', 'first')->order(10);
        });

        $this->manager->register('sorted.stats', function (StatBuilder $builder) {
            $builder->stat('دوم', 'second')->order(20);
        });

        $items = $this->manager->withoutCache()->get('sorted.stats');
        $ids   = $items->map(fn ($i) => $i->getId())->toArray();

        $this->assertSame(['first', 'second', 'third'], $ids);
    }

    public function test_converts_complex_stats_to_json(): void
    {
        $this->manager->register('json.stats', function (StatBuilder $builder) {
            $builder->stat('فروش', 'sales')
                ->value('۱۰۰,۰۰۰')
                ->icon('dollar')
                ->color('green')
                ->change('+۱۰٪', 'increase');
        });

        $json    = $this->manager->withoutCache()->toJson('json.stats');
        $decoded = json_decode($json, true);

        $this->assertNotNull($decoded);
        $this->assertIsArray($decoded);
        $this->assertCount(1, $decoded);
        $this->assertSame('sales', $decoded[0]['id']);
        $this->assertSame('۱۰۰,۰۰۰', $decoded[0]['value']);
        $this->assertSame('+۱۰٪', $decoded[0]['change']);
    }
}
