<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('country_id')->nullable();
            $table->foreignId('province_id')->nullable();
            $table->foreignId('city_id')->nullable();
            $table->string("receiver_name");
            $table->string("receiver_mobile", 16)->nullable();
            $table->text("address")->nullable();
            $table->string("postal_code")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
            $table->foreign('country_id')
                ->on('location_countries')
                ->references('id')
                ->nullOnDelete();
            $table->foreign('province_id')
                ->on('location_provinces')
                ->references('id')
                ->nullOnDelete();
            $table->foreign('city_id')
                ->on('location_cities')
                ->references('id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_addresses');
    }
};
