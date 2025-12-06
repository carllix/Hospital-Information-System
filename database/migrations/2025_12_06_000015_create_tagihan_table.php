<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('tagihan_id');
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan', 'pemeriksaan_id')->onDelete('cascade');
            $table->decimal('total_tagihan', 12, 2);
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->timestamps();

            $table->index('pemeriksaan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
