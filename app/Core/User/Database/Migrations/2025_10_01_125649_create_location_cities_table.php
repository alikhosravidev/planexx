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
        Schema::create('location_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->nullable();
            $table->string("name",64);
            $table->string("name_en",64)->nullable();
            $table->decimal("latitude",10,8)->nullable();
            $table->decimal("longitude",11,8)->nullable();
            $table->foreign('province_id')
                ->on('location_provinces')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_cities');
    }
};
