<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id('resep_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->dateTime('tanggal_resep')->useCurrent();
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'batal'])->default('menunggu');
            $table->foreignId('apoteker_id')->nullable()->constrained('staf', 'staf_id')->onDelete('set null');
            $table->text('catatan_apoteker')->nullable();
            $table->timestamps();

            $table->index('pemeriksaan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
