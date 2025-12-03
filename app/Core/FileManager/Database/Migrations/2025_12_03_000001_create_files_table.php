<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_file_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->char('uuid', 36)->unique();

            $table->string('entity_type', 100)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();

            $table->string('original_name', 255);
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('file_url', 500)->nullable();
            $table->string('disk', 50)->default('local');
            $table->string('title', 255)->nullable();

            $table->string('mime_type', 100);
            $table->string('extension', 20);
            $table->unsignedBigInteger('file_size');
            $table->string('file_hash', 64)->nullable();

            // 0:image, 1:video, 2:audio, 3:document, 4:archive, 5:other
            $table->unsignedTinyInteger('file_type')->comment('0:image, 1:video, 2:audio, 3:document, 4:archive, 5:other');
            // 0:avatar, 1:document, 2:attachment, 3:thumbnail, 4:temp, 5:other
            $table->unsignedTinyInteger('collection')->nullable()->comment('0:avatar, 1:document, 2:attachment, 3:thumbnail, 4:temp, 5:other');
            $table->boolean('is_temporary')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('aspect_ratio', 10)->nullable();

            $table->unsignedInteger('duration')->nullable();
            $table->string('resolution', 20)->nullable();
            $table->decimal('frame_rate', 5, 2)->nullable();

            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('module_name', 100)->nullable();

            $table->boolean('is_public')->default(false);

            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('uploaded_by')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->foreign('folder_id')
                ->references('id')
                ->on('core_file_folders')
                ->onDelete('set null');

            $table->index('uuid', 'idx_core_file_files_uuid');
            $table->index('uploaded_by', 'idx_core_file_files_uploader');
            $table->index('folder_id', 'idx_core_file_files_folder');
            $table->index('module_name', 'idx_core_file_files_module');
            $table->index(['entity_type', 'entity_id'], 'idx_core_file_files_entity');
            $table->index('file_type', 'idx_core_file_files_type');
            $table->index('collection', 'idx_core_file_files_collection');
            $table->index('file_hash', 'idx_core_file_files_hash');
            $table->index('is_public', 'idx_core_file_files_public');
            $table->index('is_temporary', 'idx_core_file_files_temporary');
            $table->index('expires_at', 'idx_core_file_files_expires_at');
            $table->index('is_active', 'idx_core_file_files_active');
            $table->index('deleted_at', 'idx_core_file_files_deleted');
            $table->index('created_at', 'idx_core_file_files_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_file_files');
    }
};
