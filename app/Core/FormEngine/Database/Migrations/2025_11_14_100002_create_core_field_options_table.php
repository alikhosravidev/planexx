<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_field_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id');
            $table->string('label', 200);
            $table->string('value', 100);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->foreign('field_id')->references('id')->on('core_form_fields')->onDelete('cascade');
            $table->index('field_id');
            $table->index(['field_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_field_options');
    }
};
