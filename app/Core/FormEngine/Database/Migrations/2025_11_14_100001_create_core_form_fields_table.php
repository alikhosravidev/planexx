<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('field_key', 100);
            $table->unsignedTinyInteger('field_type')->comment('0:text, 1:number, 2:date, 3:textarea, 4:radio, 5:checkbox, 6:toggle, 7:select, 8:file, 9:voice_recorder');
            $table->string('label', 200);
            $table->string('placeholder', 255)->nullable();
            $table->text('default_value')->nullable();
            $table->unsignedSmallInteger('order')->default(1);
            $table->boolean('is_required')->default(false);
            $table->json('payload')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('form_id')->references('id')->on('core_forms')->onDelete('cascade');
            $table->unique(['form_id', 'field_key']);
            $table->index('form_id');
            $table->index(['form_id', 'order']);
            $table->index('is_active');
            $table->index(['deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_form_fields');
    }
};
