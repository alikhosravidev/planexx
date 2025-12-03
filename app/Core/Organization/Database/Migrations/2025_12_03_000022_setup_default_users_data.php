<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('core_org_users')->truncate();
        DB::unprepared(file_get_contents(__DIR__ . '/../default_users.sql'));
    }

    public function down(): void
    {
        DB::table('core_org_users')->truncate();
    }
};
