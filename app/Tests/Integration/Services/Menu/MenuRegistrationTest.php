<?php

declare(strict_types=1);

namespace App\Tests\Integration\Services\Menu;

use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;
use Tests\IntegrationTestBase;

final class MenuRegistrationTest extends IntegrationTestBase
{
    private MenuManager $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new MenuManager();
    }

    public function test_registers_complete_sidebar_menu(): void
    {
        $this->manager->register('admin.sidebar', function (MenuBuilder $builder) {
            $builder->item('داشبورد', 'dashboard')->route('admin.dashboard')->icon('heroicon-o-home')->order(1);

            $builder->divider();

            $builder->group('مدیریت محتوا', 'content')->icon('heroicon-o-document')->order(10)
                ->children(function (MenuBuilder $sub) {
                    $sub->item('مقالات', 'posts')->route('admin.posts.index')->badge('5', 'blue')->order(1);
                    $sub->item('دسته‌بندی‌ها', 'categories')->route('admin.categories.index')->order(2);
                });

            $builder->group('تنظیمات', 'settings')->icon('heroicon-o-cog')->order(999)->collapsible(true)->collapsed(true)
                ->children(function (MenuBuilder $sub) {
                    $sub->item('عمومی', 'general-settings')->permission('settings.general')->order(1);
                    $sub->item('امنیت', 'security-settings')->permission('settings.security')->order(2);
                });
        });

        $items = $this->manager->withoutCache()->get('admin.sidebar');
        $array = $this->manager->withoutCache()->toArray('admin.sidebar');

        $this->assertGreaterThan(0, $items->count());
        $this->assertSame('dashboard', $items->first()->getId());
        $this->assertIsArray($array);
    }

    public function test_registers_menu_from_multiple_modules(): void
    {
        $this->manager->register('admin.sidebar', function (MenuBuilder $builder) {
            $builder->item('داشبورد', 'dashboard')->order(1);
        });

        $this->manager->register('admin.sidebar', function (MenuBuilder $builder) {
            $builder->group('بلاگ', 'blog')->order(10)->children(function (MenuBuilder $sub) {
                $sub->item('پست‌ها', 'posts')->order(1);
                $sub->item('نظرات', 'comments')->order(2);
            });
        });

        $this->manager->register('admin.sidebar', function (MenuBuilder $builder) {
            $builder->group('فروشگاه', 'shop')->order(20)->children(function (MenuBuilder $sub) {
                $sub->item('محصولات', 'products')->order(1);
                $sub->item('سفارشات', 'orders')->order(2);
            });
        });

        $items = $this->manager->withoutCache()->get('admin.sidebar');

        $this->assertCount(3, $items);
        $this->assertSame('dashboard', $items[0]->getId());
        $this->assertSame('blog', $items[1]->getId());
        $this->assertSame('shop', $items[2]->getId());
    }

    public function test_extends_existing_menu_item_from_another_module(): void
    {
        $this->manager->register('admin.sidebar', function (MenuBuilder $builder) {
            $builder->group('تنظیمات', 'settings')->order(999)->children(function (MenuBuilder $sub) {
                $sub->item('عمومی', 'general')->order(1);
            });
        });

        $this->manager->extend('admin.sidebar', 'settings', function (MenuBuilder $builder) {
            $builder->item('درگاه پرداخت', 'payment-gateway')->order(10);
        });

        $this->manager->extend('admin.sidebar', 'settings', function (MenuBuilder $builder) {
            $builder->item('پیامک', 'sms-settings')->order(20);
        });

        $items    = $this->manager->withoutCache()->get('admin.sidebar');
        $settings = $items->first();
        $children = $settings->getChildren();

        $this->assertCount(3, $children);
    }

    public function test_handles_empty_and_non_existent_menu(): void
    {
        $this->manager->register('empty.menu', function (MenuBuilder $builder) {});
        $items = $this->manager->withoutCache()->get('empty.menu');
        $this->assertCount(0, $items);
        $this->assertTrue($items->isEmpty());

        $items2 = $this->manager->withoutCache()->get('non.existent');
        $this->assertCount(0, $items2);
    }

    public function test_registers_menu_with_conditional_items(): void
    {
        $showAdvanced   = true;
        $showDeprecated = false;

        $this->manager->register('conditional.menu', function (MenuBuilder $builder) use ($showAdvanced, $showDeprecated) {
            $builder->item('همیشه نمایش', 'always')->order(1);

            if ($showAdvanced) {
                $builder->item('پیشرفته', 'advanced')->order(2);
            }

            if ($showDeprecated) {
                $builder->item('منسوخ شده', 'deprecated')->order(3);
            }
            $builder->item('پویا', 'dynamic')->activeWhen(fn () => true)->order(4);
        });

        $items = $this->manager->withoutCache()->get('conditional.menu');
        $this->assertCount(3, $items);
        $ids = $items->map(fn ($i) => $i->getId())->toArray();
        $this->assertContains('always', $ids);
        $this->assertContains('advanced', $ids);
        $this->assertNotContains('deprecated', $ids);
    }

    public function test_creates_deeply_nested_menu_structure(): void
    {
        $this->manager->register('deep.menu', function (MenuBuilder $builder) {
            $builder->group('سطح ۱', 'level-1')->children(function (MenuBuilder $sub1) {
                $sub1->group('سطح ۲', 'level-2')->children(function (MenuBuilder $sub2) {
                    $sub2->group('سطح ۳', 'level-3')->children(function (MenuBuilder $sub3) {
                        $sub3->item('آیتم نهایی', 'final-item');
                    });
                });
            });
        });

        $items = $this->manager->withoutCache()->get('deep.menu');
        $array = $this->manager->withoutCache()->toArray('deep.menu');

        $this->assertCount(1, $items);
        $this->assertSame('level-1', $array[0]['id']);
        $this->assertSame('level-2', $array[0]['children'][0]['id']);
        $this->assertSame('level-3', $array[0]['children'][0]['children'][0]['id']);
        $this->assertSame('final-item', $array[0]['children'][0]['children'][0]['children'][0]['id']);
    }
}
