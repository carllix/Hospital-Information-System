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
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id('pemeriksaan_id');
            $table->foreignId('pendaftaran_id')->unique()->constrained('pendaftaran', 'pendaftaran_id')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokter', 'dokter_id')->onDelete('cascade');
            $table->dateTime('tanggal_pemeriksaan')->useCurrent()->notNullable();
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
            $table->enum('status_pasien', ['selesai_penanganan', 'dirujuk', 'perlu_resep', 'perlu_lab'])->notNullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('pasien_id');
            $table->index('dokter_id');
            $table->index('tanggal_pemeriksaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
