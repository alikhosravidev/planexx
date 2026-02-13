<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_custom_list_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id');
            $table->string('reference_code', 100)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('list_id')
                ->references('id')
                ->on('product_custom_lists')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->index('list_id', 'idx_product_list_items_list');
            $table->index('created_by', 'idx_product_list_items_creator');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_custom_list_items');
    }
};
