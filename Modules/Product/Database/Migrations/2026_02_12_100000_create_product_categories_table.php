<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('icon_class', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->uniqueSoftDeleteBy(['slug'], 'slug');

            $table->foreign('parent_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');

            $table->index('parent_id', 'idx_product_categories_parent');
            $table->index('is_active', 'idx_product_categories_active');
            $table->index('sort_order', 'idx_product_categories_sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
