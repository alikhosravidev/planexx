<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_forms', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title', 200);
            $table->string('subtitle', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('display_mode')->default(0)->comment('0:single_page, 1:multi_step');
            $table->unsignedTinyInteger('auth_type')->default(0)->comment('0:none, 1:otp_at_start, 2:otp_at_end');
            $table->text('success_message')->nullable();
            $table->string('redirect_url', 255)->nullable();
            $table->unsignedInteger('max_submissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
            $table->index(['deleted_at']);
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_forms');
    }
};
