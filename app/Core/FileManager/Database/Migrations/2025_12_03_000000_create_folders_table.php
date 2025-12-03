<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_file_folders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                ->references('id')
                ->on('core_file_folders')
                ->onDelete('set null');

            $table->string('module_name', 100)->nullable();
            $table->string('name', 255);
            $table->boolean('is_public')->default(false);
            $table->string('color', 20)->nullable();
            $table->string('icon', 100)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id', 'idx_core_file_folders_parent');
            $table->index('module_name', 'idx_core_file_folders_module');
            $table->index('deleted_at', 'idx_core_file_folders_deleted');
            $table->index('is_public', 'idx_core_file_folders_public');
            $table->index('order', 'idx_core_file_folders_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_file_folders');
    }
};
