<?php

declare(strict_types=1);

namespace App\Utilities;

class ProviderUtility
{
    public static function corePath(string $name, string $path = ''): ?string
    {
        $corePath = app_path('Core/' . $name);

        if (is_dir($corePath) || is_file($corePath)) {
            return $corePath . (! empty($path) ? DIRECTORY_SEPARATOR . $path : '');
        }

        return null;
    }

    public static function modulePath(string $name, string $path = ''): ?string
    {
        $modulePath = base_path('Modules/' . $name);

        if (is_dir($modulePath) || is_file($modulePath)) {
            return $modulePath . (! empty($path) ? DIRECTORY_SEPARATOR . $path : '');
        }

        return null;
    }
}
