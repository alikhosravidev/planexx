<?php

declare(strict_types=1);

namespace Tests;

trait HasParaTest
{
    private static $dbName;

    private function updateDBNameForParaTest(): void
    {
        if (self::$dbName === null) {
            self::$dbName = getenv('DB_DATABASE');
        }
        $dbName    = self::$dbName;
        $testToken = getenv('TEST_TOKEN');

        if ($testToken !== false) {
            $dbName = "{$dbName}_{$testToken}";
            putenv("DB_DATABASE={$dbName}");
            $_ENV['DB_DATABASE']    = $dbName;
            $_SERVER['DB_DATABASE'] = $dbName;
        }
    }
}
