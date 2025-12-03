<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_file_favorites', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('entity_id');
            $table->string('entity_type', 150);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('cascade');

            $table->unique(['user_id', 'entity_id', 'entity_type'], 'uq_core_file_favorites_user_entity');
            $table->index('user_id', 'idx_core_file_favorites_user');
            $table->index(['entity_type', 'entity_id'], 'idx_core_file_favorites_entity');
            $table->index('created_at', 'idx_core_file_favorites_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_file_favorites');
    }
};
