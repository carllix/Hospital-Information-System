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
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('provinsi', 100)->nullable()->after('alamat');
        });

        Schema::table('dokter', function (Blueprint $table) {
            $table->string('provinsi', 100)->nullable()->after('alamat');
        });

        Schema::table('staf', function (Blueprint $table) {
            $table->string('provinsi', 100)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn('provinsi');
        });

        Schema::table('dokter', function (Blueprint $table) {
            $table->dropColumn('provinsi');
        });

        Schema::table('staf', function (Blueprint $table) {
            $table->dropColumn('provinsi');
        });
    }
};
