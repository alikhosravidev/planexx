<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::unprepared(file_get_contents(__DIR__ . '/../default_folders.sql'));
    }

    public function down(): void
    {
        DB::table('core_file_folders')->truncate();
    }
};
