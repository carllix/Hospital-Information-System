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
        Schema::create('rujukan', function (Blueprint $table) {
            $table->id('rujukan_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->string('tujuan_rujukan', 255)->nullable();
            $table->string('rs_tujuan', 255)->nullable();
            $table->string('dokter_spesialis_tujuan', 100)->nullable();
            $table->text('alasan_rujukan')->notNullable();
            $table->text('diagnosa_sementara')->nullable();
            $table->date('tanggal_rujukan')->notNullable();
            $table->foreignId('dokter_perujuk_id')->constrained('dokter', 'dokter_id')->onDelete('cascade');

            $table->index('pasien_id');
            $table->index('pemeriksaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rujukan');
    }
};
