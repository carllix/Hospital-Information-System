<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id('obat_id');
            $table->string('kode_obat', 20)->unique();
            $table->string('nama_obat', 255);
            $table->enum('kategori', ['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'lainnya']);
            $table->string('satuan', 20);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(10);
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index('kode_obat');
            $table->index('nama_obat');
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
