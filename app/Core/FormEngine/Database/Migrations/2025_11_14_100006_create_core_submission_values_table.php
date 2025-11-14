<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_submission_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('field_id');
            $table->text('value')->nullable();
            $table->string('file_url', 255)->nullable();
            $table->json('file_metadata')->nullable();
            $table->timestamps();

            $table->foreign('submission_id')->references('id')->on('core_submissions')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('core_form_fields')->onDelete('cascade');
            $table->unique(['submission_id', 'field_id']);
            $table->index('submission_id');
            $table->index('field_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_submission_values');
    }
};
