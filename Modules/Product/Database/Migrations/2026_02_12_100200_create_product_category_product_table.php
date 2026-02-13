<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');

            $table->primary(['product_id', 'category_id']);

            $table->foreign('product_id')
                ->references('id')
                ->on('product_products')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade');

            $table->index('category_id', 'idx_product_cat_prod_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category_product');
    }
};
