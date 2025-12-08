<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement(<<<SQL
            UPDATE core_org_entity_has_roles e
            JOIN (
                SELECT model_type, model_id, MIN(role_id) AS primary_role_id
                FROM core_org_entity_has_roles
                WHERE model_type = 'core_org_users'
                GROUP BY model_type, model_id
            ) p ON e.model_type = p.model_type
                AND e.model_id = p.model_id
                AND e.role_id = p.primary_role_id
            SET e.is_primary = 1
            WHERE e.model_type = 'core_org_users';
        SQL);
    }

    public function down(): void
    {
        DB::table('core_org_entity_has_roles')
            ->where('model_type', 'core_org_users')
            ->update(['is_primary' => false]);
    }
};
