<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->unsignedTinyInteger('status')->default(1)
                ->comment('1:active, 2:draft, 3:unavailable');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->index('price', 'idx_product_products_price');
            $table->index('status', 'idx_product_products_status');
            $table->index('is_featured', 'idx_product_products_featured');
            $table->index('created_by', 'idx_product_products_creator');
            $table->index('updated_by', 'idx_product_products_updater');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_products');
    }
};
