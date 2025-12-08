<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_org_entity_has_roles', static function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('model_id');
        });
    }

    public function down(): void
    {
        Schema::table('core_org_entity_has_roles', static function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
