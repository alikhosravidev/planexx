<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_custom_list_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('attribute_id');
            $table->text('value_text')->nullable();
            $table->decimal('value_number', 19, 4)->nullable();
            $table->dateTime('value_date')->nullable();
            $table->boolean('value_boolean')->nullable();

            $table->unique(['item_id', 'attribute_id'], 'uk_product_item_attribute');

            $table->foreign('item_id')
                ->references('id')
                ->on('product_custom_list_items')
                ->onDelete('cascade');

            $table->foreign('attribute_id')
                ->references('id')
                ->on('product_custom_list_attributes')
                ->onDelete('cascade');

            $table->index(['attribute_id', 'value_number'], 'idx_product_values_number');
            $table->index(['attribute_id', 'value_date'], 'idx_product_values_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_custom_list_values');
    }
};
