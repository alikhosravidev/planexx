<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_org_user_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('core_org_users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('core_org_departments')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'department_id']);
            $table->index('user_id');
            $table->index('department_id');
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_org_user_departments');
    }
};
