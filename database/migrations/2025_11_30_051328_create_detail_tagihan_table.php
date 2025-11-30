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
        Schema::create('detail_tagihan', function (Blueprint $table) {
            $table->id('detail_tagihan_id');
            $table->foreignId('tagihan_id')->constrained('tagihan', 'tagihan_id')->onDelete('cascade');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->enum('jenis_item', ['konsultasi', 'obat', 'lab', 'tindakan'])->notNullable();
            $table->string('nama_item', 255)->notNullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->notNullable();
            $table->decimal('subtotal', 12, 2)->notNullable();

            $table->index('tagihan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_tagihan');
    }
};
