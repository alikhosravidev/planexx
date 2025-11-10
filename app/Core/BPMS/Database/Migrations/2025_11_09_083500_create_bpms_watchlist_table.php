<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('bpms_watchlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('watcher_id');

            // 0:open, 1:reviewed
            $table->unsignedTinyInteger('watch_status')->default(0)->comment('0:open, 1:reviewed');
            $table->text('watch_reason')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('bpms_tasks')->onDelete('cascade');
            $table->foreign('watcher_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['task_id', 'watcher_id'], 'unique_task_watcher');
            $table->index('task_id', 'idx_task');
            $table->index('watcher_id', 'idx_watcher');
            $table->index('watch_status', 'idx_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpms_watchlist');
    }
};
