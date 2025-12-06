<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_org_departments', function (Blueprint $table) {
            $table->tinyInteger('type')->default(3)->after('description');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('core_org_departments', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
