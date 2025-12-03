<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::unprepared(file_get_contents(__DIR__ . '/../roles.sql'));
        DB::unprepared(file_get_contents(__DIR__ . '/../role_permissions.sql'));
    }

    public function down(): void
    {
        DB::table('core_org_roles')->truncate();
        DB::table('core_org_role_has_permissions')->truncate();
    }
};
