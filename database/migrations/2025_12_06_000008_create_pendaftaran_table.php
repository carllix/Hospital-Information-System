<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('pendaftaran_id');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_dokter', 'jadwal_id')->onDelete('cascade');
            $table->dateTime('tanggal_daftar')->useCurrent();
            $table->date('tanggal_kunjungan');
            $table->string('nomor_antrian', 20)->unique();
            $table->text('keluhan_utama');
            $table->foreignId('staf_pendaftaran_id')->nullable()->constrained('staf', 'staf_id')->onDelete('set null');
            $table->enum('status', ['menunggu', 'dipanggil', 'diperiksa', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps();

            $table->index('pasien_id');
            $table->index(['jadwal_id', 'tanggal_kunjungan']);
            $table->index('tanggal_kunjungan');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
