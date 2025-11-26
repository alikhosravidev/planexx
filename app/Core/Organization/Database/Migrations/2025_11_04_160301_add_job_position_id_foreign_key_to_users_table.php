<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_org_users', function (Blueprint $table) {
            $table->foreign('job_position_id')
                ->references('id')
                ->on('core_org_job_positions')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('core_org_users', function (Blueprint $table) {
            $table->dropForeign(['job_position_id']);
        });
    }
};
