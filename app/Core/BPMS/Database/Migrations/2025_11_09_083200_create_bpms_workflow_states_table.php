<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bpms_workflow_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id');
            $table->string('name', 200);
            $table->string('slug', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable();
            $table->unsignedSmallInteger('order')->default(1);

            // 0:start, 1:middle, 2:final-success, 3:final-failed, 4:final-closed
            $table->unsignedTinyInteger('position')->default(1)->comment('0:start, 1:middle, 2:final-success, 3:final-failed, 4:final-closed');
            $table->unsignedBigInteger('default_assignee_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('workflow_id')->references('id')->on('bpms_workflows')->onDelete('cascade');
            $table->foreign('default_assignee_id')->references('id')->on('users')->onDelete('set null');

            $table->unique(['workflow_id', 'slug']);

            $table->index('workflow_id', 'idx_workflow');
            $table->index(['workflow_id', 'order'], 'idx_order');
            $table->index('position', 'idx_position');
            $table->index('default_assignee_id', 'idx_assignee');
            $table->index('is_active', 'idx_active');
            $table->index('deleted_at', 'idx_deleted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpms_workflow_states');
    }
};
