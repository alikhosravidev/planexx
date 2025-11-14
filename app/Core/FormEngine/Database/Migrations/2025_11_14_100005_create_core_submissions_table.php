<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 150)->nullable();
            $table->string('user_mobile', 15)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('utm_params')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('core_forms')->onDelete('cascade');
            $table->index('form_id');
            $table->index('user_id');
            $table->index('user_mobile');
            $table->index('submitted_at');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_submissions');
    }
};
