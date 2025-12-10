<?php

declare(strict_types=1);

namespace App\Core\FileManager\Registrars;

use App\Contracts\Registry\RegistrarInterface;
use App\Contracts\Registry\RegistryManagerInterface;
use App\Services\QuickAccess\QuickAccessBuilder;

class FileManagerQuickAccessRegistrar implements RegistrarInterface
{
    public function register(RegistryManagerInterface $manager): void
    {
        $manager->register('dashboard.quick-access', function (QuickAccessBuilder $builder) {
            $builder->item('مدیریت اسناد و فایل‌ها', 'document')
                ->route('web.documents.index')
                ->icon('fa-solid fa-folder-open')
                ->color('amber')
                ->enabled()
                ->order(2);
        });
    }
}
