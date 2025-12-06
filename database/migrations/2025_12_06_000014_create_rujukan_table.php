<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rujukan', function (Blueprint $table) {
            $table->id('rujukan_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->string('tujuan_rujukan', 255);
            $table->string('rs_tujuan', 255)->nullable();
            $table->string('dokter_spesialis_tujuan', 100)->nullable();
            $table->text('alasan_rujukan');
            $table->text('diagnosa_sementara')->nullable();
            $table->date('tanggal_rujukan');
            $table->timestamp('created_at')->useCurrent();

            $table->index('pemeriksaan_id');
            $table->index('tanggal_rujukan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rujukan');
    }
};
