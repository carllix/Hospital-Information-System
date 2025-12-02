<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set location fields to NOT NULL on pasien table
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->notNullable()->change();
            $table->string('kota_kabupaten', 100)->notNullable()->change();
            $table->string('kecamatan', 100)->notNullable()->change();
            $table->string('provinsi', 100)->notNullable()->change();
        });

        // Set location fields to NOT NULL on dokter table
        Schema::table('dokter', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->notNullable()->change();
            $table->string('kota_kabupaten', 100)->notNullable()->change();
            $table->string('kecamatan', 100)->notNullable()->change();
            $table->string('provinsi', 100)->notNullable()->change();
        });

        // Set location fields to NOT NULL on staf table
        Schema::table('staf', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->notNullable()->change();
            $table->string('kota_kabupaten', 100)->notNullable()->change();
            $table->string('kecamatan', 100)->notNullable()->change();
            $table->string('provinsi', 100)->notNullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert location fields to nullable on pasien table
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->change();
            $table->string('kota_kabupaten', 100)->nullable()->change();
            $table->string('kecamatan', 100)->nullable()->change();
            $table->string('provinsi', 100)->nullable()->change();
        });

        // Revert location fields to nullable on dokter table
        Schema::table('dokter', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->change();
            $table->string('kota_kabupaten', 100)->nullable()->change();
            $table->string('kecamatan', 100)->nullable()->change();
            $table->string('provinsi', 100)->nullable()->change();
        });

        // Revert location fields to nullable on staf table
        Schema::table('staf', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->change();
            $table->string('kota_kabupaten', 100)->nullable()->change();
            $table->string('kecamatan', 100)->nullable()->change();
            $table->string('provinsi', 100)->nullable()->change();
        });
    }
};
