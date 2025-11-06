<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class PrepareParallelTests extends Command
{
    protected $signature = 'test:prepare-parallel {--fresh} {--without-seeding}';

    protected $description = 'Prepare parallel test databases with migrations and seeders';

    public function handle(): int
    {
        $startedAt = microtime(true);
        $isFresh   = $this->option('fresh');
        $processes = (int) config('test.parallel_processes_count');
        $baseName  = config('database.connections.mysql.database');
        $this->info('🚀 Preparing ' . ($processes + 1) . " test databases (base + {$processes} parallel)...");

        $this->prepareDatabases($processes, $baseName);

        $this->info('');

        $migrateCommand = $isFresh ? 'migrate:fresh --force' : 'migrate --force';
        $withSeeding    = ! $this->option('without-seeding');
        $basePath       = base_path();

        $processesArray = $this->runMigrationProcesses(
            processes     : $processes,
            baseName      : $baseName,
            migrateCommand: $migrateCommand,
            withSeeding   : $withSeeding,
            basePath      : $basePath
        );

        $failed = $this->waitForProcesses($processesArray, $baseName);

        $this->info('');

        if ($failed) {
            return $this->failedResponse($startedAt);
        }

        return $this->successResponse($startedAt);
    }

    private function prepareDatabases(int $processes, string $baseName): void
    {
        config(['database.connections.mysql.database' => 'information_schema']);
        DB::purge(config('database.default'));
        DB::reconnect(config('database.default'));

        $this->createIfNotExists($baseName);

        // Temporarily connect to information_schema to check schema existence
        config(['database.connections.mysql.database' => 'information_schema']);
        DB::purge(config('database.default'));
        DB::reconnect(config('database.default'));

        for ($i = 1; $i <= $processes; $i++) {
            $dbName = "{$baseName}_{$i}";

            $this->createIfNotExists($dbName);
        }
    }

    private function waitForProcesses(array $processesArray, string $baseName): bool
    {
        $failed = false;

        foreach ($processesArray as $i => $process) {
            $dbName = $i === 0 ? $baseName : "{$baseName}_{$i}";
            $process->wait();

            if (! $process->isSuccessful()) {
                $failed = true;
                $err    = trim($process->getErrorOutput()) ?: trim($process->getOutput());
                $msg    = $err !== '' ? explode("\n", $err)[0] : 'unknown error';
                $this->error("  ❌ Failed to prepare {$dbName}: {$msg}");
            } else {
                $this->info("  ✅ Database {$dbName} prepared successfully");
            }
        }

        return $failed;
    }

    private function runMigrationProcesses(int $processes, string $baseName, string $migrateCommand, bool $withSeeding, string $basePath): array
    {
        $processesArray = [];

        $processesArray[0] = $this->createMigrationProcess(
            dbName        : $baseName,
            migrateCommand: $migrateCommand,
            withSeeding   : $withSeeding,
            basePath      : $basePath
        );

        for ($i = 1; $i <= $processes; $i++) {
            $dbName             = "{$baseName}_{$i}";
            $processesArray[$i] = $this->createMigrationProcess(
                dbName        : $dbName,
                migrateCommand: $migrateCommand,
                withSeeding   : $withSeeding,
                basePath      : $basePath,
            );
        }

        return $processesArray;
    }

    private function createMigrationProcess(string $dbName, string $migrateCommand, bool $withSeeding, string $basePath): Process
    {
        $cmd = PHP_BINARY . ' artisan ' . $migrateCommand . ' --env=testing';

        if ($withSeeding) {
            $cmd .= ' && ' . PHP_BINARY . ' artisan db:seed --env=testing --class=TestDatabaseSeeder';
        }

        $process = Process::fromShellCommandline($cmd, $basePath, [
            'DB_DATABASE' => $dbName,
            'APP_ENV'     => 'testing',
        ]);
        $process->setTimeout(1800);
        $process->start();

        return $process;
    }

    private function createIfNotExists(string $dbName): void
    {
        $databaseExists = DB::select(
            'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?',
            [$dbName]
        );

        if (! $databaseExists) {
            DB::statement("CREATE DATABASE `{$dbName}`");
        }
    }

    private function reportDuration(float $startedAt): void
    {
        $elapsedSeconds = (int) round(microtime(true) - $startedAt);
        $this->info('');
        $this->info('⏱ Duration: ' . gmdate('H:i:s', $elapsedSeconds));
    }

    private function failedResponse($startedAt): int
    {
        $this->error('Some databases failed to prepare. Check logs above.');
        $this->reportDuration($startedAt);

        return self::FAILURE;
    }

    private function successResponse($startedAt): int
    {
        $this->info('✅ All databases prepared successfully!');
        $this->reportDuration($startedAt);

        return self::SUCCESS;
    }
}
