<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_lab', function (Blueprint $table) {
            $table->id('permintaan_lab_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->dateTime('tanggal_permintaan')->useCurrent();
            $table->enum('jenis_pemeriksaan', ['darah_lengkap', 'urine', 'gula_darah', 'kolesterol', 'radiologi', 'lainnya']);
            $table->text('catatan_permintaan')->nullable();
            $table->enum('status', ['menunggu', 'diambil_sampel', 'diproses', 'selesai', 'batal'])->default('menunggu');
            $table->foreignId('petugas_lab_id')->nullable()->constrained('staf', 'staf_id')->onDelete('set null');
            $table->timestamps();

            $table->index('pemeriksaan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_lab');
    }
};
