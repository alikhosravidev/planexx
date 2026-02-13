<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('product_custom_list_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id');
            $table->string('label');
            $table->string('key_name', 100);
            $table->unsignedTinyInteger('data_type')->default(1)
                ->comment('1:text, 2:number, 3:date, 4:boolean, 5:select');
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);

            $table->unique(['list_id', 'key_name'], 'uk_product_list_attr_key');

            $table->foreign('list_id')
                ->references('id')
                ->on('product_custom_lists')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_custom_list_attributes');
    }
};
