<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('app_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('color', 100)->nullable();
            $table->string('icon', 255)->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->uniqueSoftDeleteBy(['slug'], 'slug');

            $table->index('name', 'idx_app_tags_name');
            $table->index('slug', 'idx_app_tags_slug');
            $table->index('deleted_at', 'idx_app_tags_deleted');
            $table->index('usage_count', 'idx_app_tags_usage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_tags');
    }
};
