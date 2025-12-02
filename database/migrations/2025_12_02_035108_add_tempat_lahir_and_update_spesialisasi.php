<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tempat_lahir to pasien table
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->after('tanggal_lahir');
        });

        // Add tempat_lahir to dokter table
        Schema::table('dokter', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->after('tanggal_lahir');
        });

        // Add tempat_lahir to staf table
        Schema::table('staf', function (Blueprint $table) {
            $table->string('tempat_lahir', 100)->nullable()->after('tanggal_lahir');
        });

        // Update spesialisasi to enum type for PostgreSQL
        // Drop the type if it exists first
        DB::statement("DROP TYPE IF EXISTS spesialisasi_enum");

        DB::statement("CREATE TYPE spesialisasi_enum AS ENUM (
            'Umum',
            'Penyakit Dalam',
            'Anak',
            'Kandungan',
            'Jantung',
            'Bedah',
            'Mata',
            'THT',
            'Kulit dan Kelamin',
            'Saraf',
            'Jiwa',
            'Paru',
            'Orthopedi',
            'Urologi',
            'Radiologi',
            'Anestesi',
            'Patologi Klinik'
        )");

        DB::statement("ALTER TABLE dokter ALTER COLUMN spesialisasi TYPE spesialisasi_enum USING spesialisasi::spesialisasi_enum");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove tempat_lahir from pasien table
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn('tempat_lahir');
        });

        // Remove tempat_lahir from dokter table
        Schema::table('dokter', function (Blueprint $table) {
            $table->dropColumn('tempat_lahir');
        });

        // Remove tempat_lahir from staf table
        Schema::table('staf', function (Blueprint $table) {
            $table->dropColumn('tempat_lahir');
        });

        // Revert spesialisasi from enum to string for PostgreSQL
        DB::statement("ALTER TABLE dokter ALTER COLUMN spesialisasi TYPE VARCHAR(100)");
        DB::statement("DROP TYPE IF EXISTS spesialisasi_enum");
    }
};
