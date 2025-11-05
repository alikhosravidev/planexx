<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\DB;

trait UseOnMemoryDatabase
{
    private ?string $originalDefaultConnection = null;

    private bool $usingSqliteMemory = false;

    protected function setupOnMemoryDatabase(): void
    {
        $this->usingSqliteMemory         = true;
        $this->originalDefaultConnection = config('database.default');

        // Define an isolated sqlite in-memory connection and switch default to it
        config(
            [
                'database.connections.sqlite-memory' => [
                    'driver'                  => 'sqlite',
                    'database'                => ':memory:',
                    'prefix'                  => '',
                    'foreign_key_constraints' => true,
                ],
                'database.default' => 'sqlite-memory',
            ]
        );

        // Ensure Laravel uses the updated connection
        DB::purge('sqlite-memory');
        DB::reconnect('sqlite-memory');
    }

    protected function downOnMemoryDatabase(): void
    {
        if ($this->usingSqliteMemory === true) {
            // Disconnect and restore default connection
            DB::disconnect('sqlite-memory');

            if ($this->originalDefaultConnection !== null) {
                config(['database.default' => $this->originalDefaultConnection]);
            }

            // Remove the temporary connection from config to avoid accidental reuse
            $connections = config('database.connections');

            if (is_array($connections)) {
                unset($connections['sqlite-memory']);
                config(['database.connections' => $connections]);
            }

            // Purge any cached connection for the restored default
            if ($this->originalDefaultConnection) {
                DB::purge($this->originalDefaultConnection);
                DB::reconnect($this->originalDefaultConnection);
            }
        }
    }
}
