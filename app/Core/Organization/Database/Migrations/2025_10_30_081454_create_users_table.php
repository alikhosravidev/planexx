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
        Schema::create('core_org_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('direct_manager_id')->nullable();
            $table->foreign('direct_manager_id')
                ->references('id')
                ->on('core_org_users')
                ->onDelete('set null');

            $table->string('full_name', 200)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->char('mobile', 15);

            //['employee', 'customer', 'user']
            $table->unsignedTinyInteger('user_type')
                // default: user
                ->default(1);

            // ['b2c', 'b2b', 'b2g']
            $table->unsignedTinyInteger('customer_type')->nullable();

            $table->string('email', 150)->nullable();
            $table->char('national_code', 10)->nullable();

            $table->string('password', 128)->nullable();

            // ['male', 'female', 'other']
            $table->unsignedTinyInteger('gender')->nullable();
            $table->string('image_url', 255)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamp('birth_date')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->string('employee_code', 50)->nullable();
            $table->timestamp('employment_date')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->uniqueSoftDeleteBy(['mobile'], 'mobile');
            $table->uniqueSoftDeleteBy(['email'], 'email');
            $table->uniqueSoftDeleteBy(['national_code'], 'national_code');
            $table->uniqueSoftDeleteBy(['employee_code'], 'employee_code');

            $table->index('user_type');
            $table->index('customer_type');
            $table->index('direct_manager_id');
            $table->index('is_active');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_org_users');
    }
};
