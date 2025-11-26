<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bpms_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->nullable()->unique();
            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('current_state_id');

            $table->unsignedBigInteger('assignee_id');
            $table->unsignedBigInteger('created_by');

            // 0:low, 1:medium, 2:high, 3:urgent
            $table->unsignedTinyInteger('priority')->default(1)->comment('0:low, 1:medium, 2:high, 3:urgent');

            $table->decimal('estimated_hours', 5, 2)->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('next_follow_up_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('workflow_id')->references('id')->on('bpms_workflows')->onDelete('restrict');
            $table->foreign('current_state_id')->references('id')->on('bpms_workflow_states')->onDelete('restrict');
            $table->foreign('assignee_id')->references('id')->on('core_org_users')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('core_org_users')->onDelete('restrict');

            $table->index('workflow_id', 'idx_workflow');
            $table->index('current_state_id', 'idx_current_state');
            $table->index('assignee_id', 'idx_assignee');
            $table->index('created_by', 'idx_creator');
            $table->index('priority', 'idx_priority');
            $table->index('next_follow_up_date', 'idx_next_follow_up');
            $table->index('due_date', 'idx_due_date');
            $table->index('deleted_at', 'idx_deleted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpms_tasks');
    }
};
