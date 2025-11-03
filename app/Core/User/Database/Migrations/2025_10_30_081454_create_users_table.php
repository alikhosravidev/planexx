<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('direct_manager_id')->nullable();
            $table->foreign('direct_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->string('full_name', 200);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->char('mobile', 15)->unique();

            //['employee', 'customer', 'user']
            $table->unsignedTinyInteger('user_type')
                // default: user
                ->default(1);

            // ['b2c', 'b2b', 'b2g']
            $table->unsignedTinyInteger('customer_type')->nullable();

            $table->string('email', 150)->unique()->nullable();
            $table->char('national_code', 10)->nullable()->unique();

            $table->string('password', 128)->nullable();

            // ['male', 'female', 'other']
            $table->unsignedTinyInteger('gender')->nullable();
            $table->string('image_url', 255)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamp('birth_date')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            $table->unsignedBigInteger('job_position_id')->nullable();
            $table->foreign('job_position_id')
                ->references('id')
                ->on('job_positions')
                ->onDelete('set null');
            $table->string('employee_code', 50)->nullable()->unique();
            $table->timestamp('employment_date')->nullable();
            $table->index('job_position_id');
            */

            $table->index('mobile');
            $table->index('email');
            $table->index('national_code');
            $table->index('user_type');
            $table->index('customer_type');
            $table->index('direct_manager_id');
            $table->index('is_active');
            $table->index('deleted_at');
        });

        DB::statement('ALTER TABLE users ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
