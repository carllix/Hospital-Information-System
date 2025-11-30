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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('pendaftaran_id');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->dateTime('tanggal_daftar')->useCurrent()->notNullable();
            $table->string('nomor_antrian', 20)->notNullable();
            $table->enum('jenis_kunjungan', ['baru', 'lama'])->notNullable();
            $table->text('keluhan_utama')->notNullable();
            $table->foreignId('staf_pendaftaran_id')->nullable()->constrained('staf', 'staf_id')->onDelete('set null');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai'])->default('menunggu');
            $table->timestamp('created_at')->useCurrent();

            $table->index('pasien_id');
            $table->index('tanggal_daftar');
            $table->index('status');
            $table->index('nomor_antrian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
