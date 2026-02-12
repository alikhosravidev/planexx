<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('bpms_workflow_states', function (Blueprint $table) {
            $table->dropUnique(['workflow_id', 'slug']);
            $table->dropColumn('slug');
        });
    }

    public function down(): void
    {
        Schema::table('bpms_workflow_states', function (Blueprint $table) {
            $table->string('slug', 50)->nullable()->after('name');
            $table->unique(['workflow_id', 'slug']);
        });
    }
};
