<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('core_form_fields', function (Blueprint $table) {
            $table->dropUnique('core_form_fields_form_id_field_key_unique');
            $table->uniqueSoftDeleteBy(['form_id', 'field_key'], 'core_form_fields_form_id_field_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('core_form_fields', function (Blueprint $table) {
            $table->dropUniqueSoftDeleteBy(['form_id', 'field_key'], 'core_form_fields_form_id_field_key_unique');
            $table->unique(['form_id', 'field_key'], 'core_form_fields_form_id_field_key_unique');
        });
    }
};
