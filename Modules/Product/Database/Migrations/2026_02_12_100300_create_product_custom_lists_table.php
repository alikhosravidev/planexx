<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_custom_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon_class', 50)->default('fa-list');
            $table->string('color', 7)->default('#000000');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->index('created_by', 'idx_product_custom_lists_creator');
            $table->index('is_active', 'idx_product_custom_lists_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_custom_lists');
    }
};
