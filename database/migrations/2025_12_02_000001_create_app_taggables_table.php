<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('app_entity_has_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->string('entity_type', 100);
            $table->unsignedInteger('entity_id');
            $table->timestamps();

            $table->foreign('tag_id')
                ->references('id')
                ->on('app_tags')
                ->cascadeOnDelete();

            $table->unique(['tag_id', 'entity_type', 'entity_id'], 'unique_tag_entity');
            $table->index(['tag_id'], 'idx_entities_tag');
            $table->index(['entity_type', 'entity_id'], 'idx_entities_entity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_entity_has_tags');
    }
};
