<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bpms_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 50)->nullable()->unique();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('workflow_owner_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('workflow_owner_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->index('department_id', 'idx_department');
            $table->index('workflow_owner_id', 'idx_process_manager');
            $table->index('created_by', 'idx_creator');
            $table->index('is_active', 'idx_active');
            $table->index('deleted_at', 'idx_deleted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpms_workflows');
    }
};
