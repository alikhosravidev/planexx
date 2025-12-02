<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_org_roles', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
        });

        Schema::table('core_org_permissions', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('core_org_roles', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('core_org_permissions', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
