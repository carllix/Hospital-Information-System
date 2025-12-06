<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tagihan', function (Blueprint $table) {
            $table->id('detail_tagihan_id');
            $table->foreignId('tagihan_id')->constrained('tagihan', 'tagihan_id')->onDelete('cascade');
            $table->foreignId('layanan_id')->nullable()->constrained('layanan', 'layanan_id')->onDelete('set null');
            $table->foreignId('detail_resep_id')->nullable()->constrained('detail_resep', 'detail_resep_id')->onDelete('set null');
            $table->foreignId('hasil_lab_id')->nullable()->constrained('hasil_lab', 'hasil_lab_id')->onDelete('set null');
            $table->enum('jenis_item', ['konsultasi', 'tindakan', 'obat', 'lab']);
            $table->string('nama_item', 255);
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('tagihan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tagihan');
    }
};
