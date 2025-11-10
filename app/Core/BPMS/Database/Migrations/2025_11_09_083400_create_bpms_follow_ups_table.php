<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bpms_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');

            // 0:follow_up, 1:state_transition, 2:user_action, 3:watcher_review
            $table->unsignedTinyInteger('type')->comment('0:follow_up, 1:state_transition, 2:user_action, 3:watcher_review');

            $table->text('content')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('previous_assignee_id')->nullable();
            $table->unsignedBigInteger('new_assignee_id')->nullable();

            $table->unsignedBigInteger('previous_state_id')->nullable();
            $table->unsignedBigInteger('new_state_id')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('task_id')->references('id')->on('bpms_tasks')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('previous_assignee_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('new_assignee_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('previous_state_id')->references('id')->on('bpms_workflow_states')->onDelete('restrict');
            $table->foreign('new_state_id')->references('id')->on('bpms_workflow_states')->onDelete('restrict');

            $table->index('task_id', 'idx_task');
            $table->index('created_by', 'idx_user');
            $table->index('type', 'idx_type');
            $table->index('created_at', 'idx_created');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('bpms_follow_ups');
    }
};
