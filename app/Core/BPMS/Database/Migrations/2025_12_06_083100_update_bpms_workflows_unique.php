<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('bpms_workflows', function (Blueprint $table) {
            $table->dropUnique('bpms_workflows_slug_unique');
            $table->uniqueSoftDeleteBy(['slug'], 'bpms_workflows_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bpms_workflows', function (Blueprint $table) {
            $table->dropUniqueSoftDeleteBy(['slug'], 'bpms_workflows_slug_unique');
            $table->unique('slug');
        });
    }
};
