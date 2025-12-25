<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('migrations')
            ->where('migration', '=', '2025_12_03_000001_setup_default_users_data')
            ->update(['migration' => '2025_12_08_000001_setup_default_users_data']);
    }

    public function down(): void
    {
    }
};
