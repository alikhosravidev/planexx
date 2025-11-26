<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_org_users', function (Blueprint $table) {
            $table->unsignedBigInteger('job_position_id')->nullable()
                ->after('direct_manager_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('core_org_users', function (Blueprint $table) {
            $table->dropIndex(['job_position_id']);
            $table->dropColumn('job_position_id');
        });
    }
};
