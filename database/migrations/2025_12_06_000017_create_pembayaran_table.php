<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('pembayaran_id');
            $table->foreignId('tagihan_id')->constrained('tagihan', 'tagihan_id')->onDelete('cascade');
            $table->dateTime('tanggal_bayar')->useCurrent();
            $table->enum('metode_pembayaran', ['tunai', 'debit', 'kredit', 'transfer', 'qris', 'asuransi']);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->foreignId('kasir_id')->constrained('staf', 'staf_id')->onDelete('cascade');
            $table->string('no_kwitansi', 30)->unique();
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('tagihan_id');
            $table->index('no_kwitansi');
            $table->index('tanggal_bayar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
