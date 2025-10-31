<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PrepareParallelTests extends Command
{
    protected $signature = 'test:prepare-parallel {--fresh} {--without-seeding}';

    protected $description = 'Prepare parallel test databases with migrations and seeders';

    public function handle()
    {
        $isFresh   = $this->option('fresh');
        $processes = (int) config('test.parallel_processes_count');
        $baseName  = config('database.connections.mysql.database');
        $this->info("🚀 Preparing $processes parallel test databases...");

        for ($i = 1; $i <= $processes; $i++) {
            $dbName = "{$baseName}_{$i}";
            $this->info("📊 Processing database {$dbName}...");

            $databaseExists = DB::select(
                'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?',
                [$dbName]
            );

            if (! $databaseExists) {
                DB::statement("CREATE DATABASE `{$dbName}`");
                $this->info("🗄️ Created database {$dbName}");
            }

            putenv("DB_DATABASE={$dbName}");
            config(['database.connections.mysql.database' => $dbName]);
            DB::purge(config('database.default'));
            DB::reconnect(config('database.default'));

            try {
                $migrateCommand = $isFresh ? 'migrate:fresh' : 'migrate';
                $this->info("🔄 Running {$migrateCommand} on {$dbName}...");
                Artisan::call($migrateCommand, ['--env' => 'testing']);
                $this->info("✅ Migrations completed for {$dbName}");

                if (! $this->option('without-seeding')) {
                    $this->info("🌱 Seeding database {$dbName}...");
                    Artisan::call('db:seed', ['--env' => 'testing', '--class' => 'TestDatabaseSeeder']);
                    $this->info("✅ Seeding completed for {$dbName}");
                }
                $this->info("🎉 Database {$dbName} prepared successfully!");
            } catch (Throwable $th) {
                $this->error("❌ Error preparing database {$dbName}: {$th->getMessage()}");
                Log::error($th->getMessage(), compact('th'));
            }
        }

        $this->info('');
        $this->info('✅ All databases prepared successfully!');
    }
}
