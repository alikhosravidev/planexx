<?php

declare(strict_types=1);

namespace App\Core\FileManager\Registrars;

use App\Contracts\MenuRegistrar;
use App\Services\Menu\MenuBuilder;
use App\Services\Menu\MenuManager;

class FileManagerMenuRegistrar implements MenuRegistrar
{
    public function register(MenuManager $menu): void
    {
        $menu->register('dashboard.sidebar', function (MenuBuilder $menu) {
            $menu->item('مدیریت اسناد', 'documents')
                ->route('web.documents.index')
                ->icon('fa-solid fa-folder-open')
                ->order(3);
        });

        $menu->register('documents.sidebar', function (MenuBuilder $menu) {
            $menu->item('همه فایل‌ها', 'documents-all')
                ->route('web.documents.index')
                ->icon('fa-solid fa-file')
                ->order(1);

            $menu->item('علاقه‌مندی‌ها', 'documents-favorites')
                ->route('web.documents.favorites')
                ->icon('fa-solid fa-star')
                ->order(2);

            $menu->item('اخیراً مشاهده شده', 'documents-recent')
                ->route('web.documents.recent')
                ->icon('fa-solid fa-clock-rotate-left')
                ->order(3);

            $menu->item('فایل‌های موقت', 'documents-temporary')
                ->route('web.documents.temporary')
                ->icon('fa-solid fa-hourglass-half')
                ->order(4);
        });
    }
}
