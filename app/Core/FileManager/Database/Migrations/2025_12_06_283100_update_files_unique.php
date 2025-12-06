<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_file_files', function (Blueprint $table) {
            $table->dropUnique('core_file_files_uuid_unique');
            $table->uniqueSoftDeleteBy(['uuid'], 'core_file_files_uuid_unique');
        });
    }

    public function down(): void
    {
        Schema::table('core_file_files', function (Blueprint $table) {
            $table->dropUniqueSoftDeleteBy(['uuid'], 'core_file_files_uuid_unique');
            $table->unique('uuid');
        });
    }
};
