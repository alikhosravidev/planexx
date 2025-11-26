<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('core_org_personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable', 'personal_access_tokens_tokenable_index');
            $table->text('name');
            $table->string('token', 64)->unique('personal_access_tokens_token_unique');
            $table->text('abilities')->nullable();
            $table->string('ip', 16)->nullable();
            $table->char('fingerprint', 40)->nullable();
            $table->string('user_agent', 1024)->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index('personal_access_tokens_expires_at_index');
            $table->timestamp('logout_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_org_personal_access_tokens');
    }
};
