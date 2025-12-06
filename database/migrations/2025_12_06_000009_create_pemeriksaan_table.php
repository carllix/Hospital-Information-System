<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id('pemeriksaan_id');
            $table->foreignId('pendaftaran_id')->unique()->constrained('pendaftaran', 'pendaftaran_id')->onDelete('cascade');
            $table->dateTime('tanggal_pemeriksaan')->useCurrent();
            $table->text('anamnesa')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->string('tekanan_darah', 20)->nullable();
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->text('diagnosa')->nullable();
            $table->string('icd10_code', 10)->nullable();
            $table->text('tindakan_medis')->nullable();
            $table->text('catatan_dokter')->nullable();
            $table->enum('status', ['dalam_pemeriksaan', 'selesai'])->default('dalam_pemeriksaan');
            $table->timestamps();

            $table->index('pendaftaran_id');
            $table->index('tanggal_pemeriksaan');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
