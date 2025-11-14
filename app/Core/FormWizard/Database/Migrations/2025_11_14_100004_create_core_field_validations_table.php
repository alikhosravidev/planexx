<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_field_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id');
            $table->unsignedBigInteger('validation_rule_id');
            $table->string('value', 255)->nullable();
            $table->string('error_message', 255)->nullable();
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('core_form_fields')->onDelete('cascade');
            $table->foreign('validation_rule_id')->references('id')->on('core_validation_rules')->onDelete('restrict');
            $table->index('field_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_field_validations');
    }
};
