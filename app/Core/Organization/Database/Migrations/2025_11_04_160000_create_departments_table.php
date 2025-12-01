<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('core_org_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()
                ->constrained('core_org_departments')->nullOnDelete();
            $table->string('name', 100);
            $table->string('code', 50)->nullable()->unique();
            $table->foreignId('manager_id')->nullable()->constrained('core_org_users');
            $table->string('image_url', 255)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('icon', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('manager_id');
            $table->index('is_active');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_org_departments');
    }
};
