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
        Schema::create('resep', function (Blueprint $table) {
            $table->id('resep_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokter', 'dokter_id')->onDelete('cascade');
            $table->dateTime('tanggal_resep')->useCurrent()->notNullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->foreignId('apoteker_id')->nullable()->constrained('staf', 'staf_id')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();

            $table->index('pasien_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
