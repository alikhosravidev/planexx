<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('bpms_workflow_states', function (Blueprint $table) {
            $table->dropUnique('bpms_tasks_slug_unique');
            $table->uniqueSoftDeleteBy(['workflow_id', 'slug'], 'bpms_tasks_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bpms_workflow_states', function (Blueprint $table) {
            $table->dropUniqueSoftDeleteBy(['workflow_id', 'slug'], 'bpms_tasks_slug_unique');
            $table->unique(['workflow_id', 'slug']);
        });
    }
};
