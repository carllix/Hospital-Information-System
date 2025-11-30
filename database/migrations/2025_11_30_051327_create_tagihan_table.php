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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('tagihan_id');
            $table->foreignId('pendaftaran_id')->nullable()->constrained('pendaftaran', 'pendaftaran_id')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasien', 'pasien_id')->onDelete('cascade');
            $table->enum('jenis_tagihan', ['konsultasi', 'obat', 'lab', 'tindakan'])->notNullable();
            $table->decimal('subtotal', 12, 2)->notNullable();
            $table->decimal('total_tagihan', 12, 2)->notNullable();
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
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
        Schema::dropIfExists('tagihan');
    }
};
